<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\UserTrait;

class PayeePayment extends ApiModel implements ApiQueryableInterface
{
    protected $table = 'payee_payment';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected function accessors() {
        return [
            "company_name" => $this->company->name,
        ];
    }


    public static function apiQuery()
    {
        $query = self::query();
        if (Auth::user()->isPublisherAdmin() || Auth::user()->isPublisher())
        {
            $query->where(PayeePayment::table().'.company_id', '=', Auth::user()["company_id"]);
        }

        if (Auth::user()->isPayee())
        {
            $query->where(PayeePayment::table().".payee_code", '=', Auth::user()["code"]);
        }
        return $query;
    }

    public function payee()
    {
        return $this->belongsTo('User', 'payee_code', 'code');
    }

    public function company()
    {
        return $this->belongsTo('Company', 'company_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo('Client', 'client_code', 'code');
    }

    public function royaltyPayments()
    {
        return $this->hasMany('RoyaltyPayment','payee_payment_id','id');
    }

    public static function apiCreatePayeePayment()
    {
        $inputs             = Input::all();
        $royaltyPaymentsIds = $inputs["ids"];
        unset($inputs["ids"]);
        $model = self::createEntity(__CLASS__, $inputs);
        RoyaltyPayment::whereIn("id", $royaltyPaymentsIds)->update(["payee_payment_id" => $model->id]);
        return $model;
    }

    private function addAdvancePayment($advance)
    {
        $advanceAmountLeftToBePaid = $advance->amountLeftToPay();

        $advancedPayment = $this->amount;
        if ($advanceAmountLeftToBePaid <= $advancedPayment) {
            $advancedPayment = $advanceAmountLeftToBePaid;
            $advance->status = "complete";
        }
        AdvancePayment::create(["advance_id" => $advance->id,
                                "amount" => $advancedPayment,
                                "payee_payment_id" => $this->id]);
        $advance->save();
    }

    private function distributePayeePaymentToAdvancePayments()
    {
        $currentAdvance = Advance::where('payee_code', '=', $this->payee_code)
                                  ->where('company_id', '=', $this->company_id)
                                  ->where('status', '=', 'incomplete')
                                  ->orderBy('start_date')
                                  ->first();

        if ($currentAdvance != null)
        {
            $amountLeft = $currentAdvance->amountLeftToPay();
            $this->addAdvancePayment($currentAdvance, $this);

            if ($this->amount - $amountLeft <= 0)
            {
                $this->amount_paid = 0;
            }
            else
            {
                $this->amount_paid = $this->amount - $amountLeft;
            }
            $this->save();
        }
    }


    public function markAsPaid($paymentDate, $notes=null)
    {
        $this->status = "paid";
        $this->notes = $notes;
        $this->payment_date = $paymentDate;
        $this->amount_paid = $this->amount;
        $this->marked_as_paid_at = date("Y-m-d H:i:s");
        $this->save();
        $this->distributePayeePaymentToAdvancePayments();
    }

    public static function apiMarkAllAsPaid()
    {
        if (count(Input::all()["ids"]) > 0)
            foreach (Input::all()["ids"] as $payeePaymentId) {
                $payeePayment = PayeePayment::find($payeePaymentId);
                $payeePayment->markAsPaid(Input::all()["payment_date"], Input::all()["notes"]);
        }
        else {
            $payeePayments = PayeePayment::unpaid()->get();
            foreach ($payeePayments as $payeePayment)
                $payeePayment->markAsPaid(Input::all()["payment_date"]);
        }
    }


    public static $PAYMENT_CSV_SQL_DATA = ["payee_payment.id",
        "payee_payment.amount",
        "user.name",
        "payee_payment.marked_as_paid_at",
        "payee_payment.payment_details",
        "payee_payment.payment_type",
        "payee_payment.amount_paid",
        "payee_payment.notes",
        "payee_payment.check_number",
        "payee_payment.year",
        "payee_payment.quarter",
        "payee_payment.month",
        "payee_payment.half_year"
    ];

    public static $PAYMENT_CSV_HEADER = ["Id",
        "Amount",
        "Payee Name",
        "Paid Date (yyyy/mm/dd or mm/dd/yy)",
        "Payment Details (Accepted values: CURRENT PERIOD|CURRENT PERIOD AND PRIOR BALANCE|ADVANCE)",
        "Payment Type (Accepted values: CHECK|ACH|WIRE)",
        "Amount Paid",
        "Notes",
        "Check Number",
        "Statement Year",
        "Statement Quarter",
        "Statement Month",
        "Statement Half Year"
    ];

    private static function formatToExcel($results)
    {
        foreach ($results as $key => $result) {
            $result["amount"] = str_replace('.', ',', $result["amount"]);
            $result["amount_paid"] = str_replace('.', ',', $result["amount_paid"]);
            $results[$key] = $result;
        }
        return $results;
    }

    public static function apiExportUnpaidStatements() {
        $results = PayeePayment::unpaid()->with('payee')
            ->join('user', 'payee_payment.payee_code', '=', 'user.code')
            ->orderBy('id', 'desc')
            ->get(PayeePayment::$PAYMENT_CSV_SQL_DATA)->toArray();
        return PayeePayment::exportStatementToCsv($results);
    }

    public static function apiExportUnpaidPayeeStatements()
    {
        if (isset($_GET['payeeCode'])) {
            $results = PayeePayment::unpaidWherePayeeCode($_GET['payeeCode'])
                ->join('user', 'payee_payment.payee_code', '=', 'user.code')
                ->orderBy('id', 'desc')
                ->get(PayeePayment::$PAYMENT_CSV_SQL_DATA)->toArray();
            return PayeePayment::exportStatementToCsv($results);
        }
    }

    private static function exportStatementToCsv($results)
    {
        return ["content" => CsvFileService::toCsv($results, '"'.join('","',PayeePayment::$PAYMENT_CSV_HEADER).'"', '', ','),
            "content_type" => "text/csv",
            "filename" => "unpaidStatements.csv"];
    }

    public static function upload()
    {
        $uploadPath = storage_path('temp')."/".basename($_FILES['file']['name']);
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath))
            return null;

        $payeePayments = CsvFileService::csvToArray($uploadPath);

        foreach ($payeePayments as $payeePayment)
            self::updateOrCreatePayeePayment($payeePayment);

        unlink($uploadPath);
        return "success";
    }

    private static function formatFromExcel($payeePayment)
    {
        //format date
        if (!empty($payeePayment[3])) {
            $paymentDate = date_create_from_format('Y/m/d', $payeePayment[3]);
            if ($paymentDate->format("Y") < 1970 && strlen($payeePayment[3])==8) {
                $paymentDate = date_create_from_format('m/d/y', $payeePayment[3]);
            }

            $payeePayment[3] = date_format($paymentDate,'y-m-d');
        }

        $updateablePayeePayment = [];
        foreach ($payeePayment as $key => $value) {
            if (self::$PAYMENT_CSV_SQL_DATA[$key] == 'user.name') continue;

            if (!empty($value))
                $updateablePayeePayment[self::$PAYMENT_CSV_SQL_DATA[$key]] = $value;
            else
                $updateablePayeePayment[self::$PAYMENT_CSV_SQL_DATA[$key]] = null;
        }

        if (isset($updateablePayeePayment['payee_payment.marked_as_paid_at']))
            $updateablePayeePayment['status'] = 'paid';

        return $updateablePayeePayment;
    }

    public static function updateOrCreatePayeePayment($payeePayment)
    {
        $payeePayment = self::formatFromExcel($payeePayment);
        if (isset($payeePayment['payee_payment.id']) && is_int(intval($payeePayment['payee_payment.id']))) {
            $payment = PayeePayment::find($payeePayment['payee_payment.id']);
            $payment->update($payeePayment);
        } else {
            $payeePayment['company_id'] = Auth::user()["company_id"];
            $payment = PayeePayment::create($payeePayment);
        }
        if (isset($payeePayment['status']) && $payeePayment['status'] == 'paid')
            $payment->distributePayeePaymentToAdvancePayments();
    }

    public static function unpaid()
    {
        return self::apiQuery()->where("status", "=", "unpaid");
    }

    public static function paid()
    {
        return self::apiQuery()->where("status", "=", "paid");
    }

    public static function paidWherePayeeCode($payeeCode)
    {
        return self::paid()->where('payee_code', '=', $payeeCode);
    }

    public static function unpaidWherePayeeCode($payeeCode)
    {
        return self::unpaid()->where('payee_code', '=', $payeeCode);
    }
}