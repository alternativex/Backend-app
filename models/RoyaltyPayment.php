<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\UserTrait;

class RoyaltyPayment extends ApiModel implements ApiQueryableInterface
{
    protected $table = 'royalty_payment';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function payeePayment()
    {
        return $this->belongsTo('PayeePayment','payee_payment_id','id');
    }

    public static function apiAmountReceivedByDateFrom()
    {
        return self::sumGroupedByDateFrom("amount_received");
    }

    public static function apiAmountEarnedByDateFrom()
    {
        return self::sumGroupedByDateFrom("amount_earned");
    }

    public static function apiDetachFromPayeePayment()
    {
        $decrementsAmounts = RoyaltyPayment::whereIn('id', array_values(Input::all()))
            ->groupBy("payee_payment_id")
            ->orderBy("payee_payment_id", "asc")
            ->select(DB::raw('sum(amount_received) AS amount'), 'payee_payment_id')
            ->get();

        foreach ($decrementsAmounts as $decrement) {
            $payeePayment = PayeePayment::find($decrement->payee_payment_id);
            $payeePayment->amount -= $decrement->amount;
            if ($payeePayment->amount == 0)
                $payeePayment->delete();
            else
                $payeePayment->save();
        }

        RoyaltyPayment::whereIn('id', array_values(Input::all()))->update(["payee_payment_id" => null]);
    }

    public static function unattachedPayments()
    {
        return self::apiQuery()->whereNull(RoyaltyPayment::table().".payee_payment_id");
    }

    public static function attachedPayments()
    {
        return self::apiQuery()->whereNotNull(RoyaltyPayment::table().".payee_payment_id");
    }

    public static function unattachedPaymentsPerPayee($payeeCode)
    {
        return self::unattachedPayments()->where('payee_code', '=', $payeeCode);
    }

    public static function attachedPaymentsPerPayee($payeeCode)
    {
        return self::attachedPayments()->where(RoyaltyPayment::table().'.payee_code', '=', $payeeCode);
    }

    public static function sumGroupedByDateFrom($sumColumn)
    {
        $query = self::query();
        $query->select(DB::raw('sum('.$sumColumn.') AS amount'), 'date_period_from as date')
            ->whereNotNull('date_period_from')
            ->groupBy('date_period_from')
            ->orderBy('date_period_from');
        if (!Auth::user()->isAdmin())
            $query->where('company_id', '=', Auth::user()["company_id"]);
        return $query->get();
    }

    public static function apiQuery()
    {
        $query = self::query();
        if (Auth::user()->isPublisherAdmin() || Auth::user()->isPublisher())
        {
            $query->where(RoyaltyPayment::table().'.company_id', '=', Auth::user()["company_id"]);
        }

        if (Auth::user()->isPayee())
        {
            $query->where(RoyaltyPayment::table().".payee_code", '=', Auth::user()["code"]);
        }
        return $query;
    }

    public static $PAYMENTS_CSV_HEADER = ["Company Name",
        "Company Code",
        "Payee Name",
        "Payee Code",
        "Client Name",
        "Client Code",
        "Song Title",
        "Song Code",
        "Composer(s)",
        "Source Name",
        "Source Code",
        "Income Type Description",
        "Income Type",
        "Percent Received",
        "Amount Received",
        "Share",
        "Contractual Rate",
        "Contractual Code",
        "Effective Rate",
        "Amount Earned",
        "Catalogue Number",
        "Units",
        "Price",
        "Date Period (From)",
        "Date Period (To)",
        "Territory Name",
        "Territory Code",
        "Production/Episode",
        "Production/Episode Code",
        "Notes",
        "Currency",
        "Statement ID",
        "Statement Line"
    ];


    public static function apiExportPaymentsTemplate() {
        return ["content" => CsvFileService::toCsv([], join(",",PayeePayment::$PAYMENTS_CSV_HEADER)),
            "content_type" => "text/csv",
            "filename" => "payments_template.csv"];
    }

    public static function unattachedPaymentsForFile($royaltyPaymentFileId)
    {
        return RoyaltyPayment::where('royalty_payment_file_id', '=', $royaltyPaymentFileId)->whereNull('payee_payment_id');
    }

    public static function attachedPaymentsForFile($royaltyPaymentFileId)
    {
        return RoyaltyPayment::where('royalty_payment_file_id', '=', $royaltyPaymentFileId)->whereNotNull('payee_payment_id');
    }

    public static function unattachedPaymentsForFileAndPayee($royaltyPaymentFileId, $payeeCode)
    {
        return RoyaltyPayment::unattachedPaymentsForFile($royaltyPaymentFileId)->where("payee_code", "=", $payeeCode);
    }

    public static function groupedByPayeeCode($royaltyPaymentFileId)
    {
        return RoyaltyPayment::unattachedForFileGroupedByField($royaltyPaymentFileId, "payee_code");
    }

    public static function groupedByClientCode($royaltyPaymentFileId)
    {
        return RoyaltyPayment::unattachedForFileGroupedByField($royaltyPaymentFileId, "client_code");
    }

    public static function unattachedForFileGroupedByField($royaltyPaymentFileId, $groupBy)
    {
        return RoyaltyPayment::unattachedPaymentsForFile($royaltyPaymentFileId)
            ->groupBy($groupBy)
            ->orderBy($groupBy, "asc");
    }

    public static function attachedGroupedByPayeeCode($royaltyPaymentFileId)
    {
        return RoyaltyPayment::attachedPaymentsForFile($royaltyPaymentFileId)
            ->groupBy("payee_code")
            ->orderBy("payee_code", "asc");
    }

    public static function sumAmountReceivedPaidPerPayee($payeeCode)
    {
        $attachedPaid = self::attachedPaymentsPerPayee($payeeCode)
            ->join(PayeePayment::table()." as pp", 'pp.id', '=', 'payee_payment_id')
            ->where('pp.status', '=', 'paid')
            ->select(DB::raw('sum(amount_received) AS amount'))
            ->first();

//        $queries = DB::getQueryLog();
//        $last_query = end($queries);
//
//        echo "<pre>";
//        print_r($queries);
//        echo "</pre>";
//        die("moare aici");

        return ($attachedPaid != null ? $attachedPaid->amount : 0);
    }

    public static function sumAmountReceivedUnpaidPerPayee($payeeCode)
    {
        $unattachedAmount = self::unattachedPaymentsPerPayee($payeeCode)
            ->select(DB::raw('sum(amount_received) AS amount'))
            ->first();
        $attachedUnpaid = self::attachedPaymentsPerPayee($payeeCode)
            ->join(PayeePayment::table()." as pp", 'pp.id', '=', 'payee_payment_id')
            ->where('pp.status', '=', 'unpaid')
            ->select(DB::raw('sum(amount_received) AS amount'))
            ->first();
        return ($unattachedAmount != null ? $unattachedAmount->amount : 0) +
        ($attachedUnpaid != null ? $attachedUnpaid->amount : 0);
    }

    public static function sumAmountReceivedGroupedByPayeeCode($royaltyPaymentFileId)
    {
        return self::groupedByPayeeCode($royaltyPaymentFileId)->select(DB::raw('sum(amount_received) AS amount'), 'payee_code', 'client_code');
    }

    public static function paymentsPerPayee($payeeCode)
    {
        return self::apiQuery()->where('payee_code', '=', $payeeCode);
    }

    private static function downloadHeader()
    {
        return ["id", "payee_name", "payee_code", "client_name", "client_code", "song_title", "song_code"];
    }

    public static function apiCsv()
    {
        $results = self::paymentsPerPayee(Auth::user()["code"])->select(self::downloadHeader())->get()->toArray();
        return ["content" => CsvFileService::toCsv($results, implode(",", self::downloadHeader())),
                "content_type" => "text/csv",
                "filename" => "payments.csv"];
    }

    public static function apiXls()
    {
        $results = self::paymentsPerPayee(Auth::user()["code"])->select(self::downloadHeader())->get()->toArray();
        return ["content" => CsvFileService::toXls($results, implode("\t", self::downloadHeader())),
                "content_type" => "application/vnd.ms-excel",
                "filename" => "payments.xls"];
    }


    public static function detectNewLineChar($line)
    {
        if (strpos($line, "\r\n") !== false)
        {
            return "LINES TERMINATED BY '\r\n'";
        }

        if (strpos($line, "\r") !== false)
        {
            return "LINES TERMINATED BY '\r'";
        }

        if (strpos($line, "\n") !== false)
        {
            return "LINES TERMINATED BY '\n'";
        }

    }

    public static function loadFromFile(RoyaltyPaymentFile $rpf)
    {
        $firstLine = CsvFileService::getFileFirstLine($rpf->path);
        $header = explode(',', strtolower(trim($firstLine)));
        $fieldsToFileHeaderPos = RoyaltyPayment::fieldsToFileHeaderPositions();
        if (count($header) == count(RoyaltyPayment::fieldsToFileHeaderPositionsOld())){
            $fieldsToFileHeaderPos = RoyaltyPayment::fieldsToFileHeaderPositionsOld();
        }

        $query = "LOAD DATA LOCAL INFILE ".DB::connection()->getPdo()->quote($rpf->path)."
            INTO TABLE ".RoyaltyPayment::table()."
            CHARACTER SET 'utf8'
            FIELDS TERMINATED BY ','
            ENCLOSED BY '\"' " .
            RoyaltyPayment::detectNewLineChar($firstLine)."
            IGNORE 1 LINES
            (@col".implode(",@col", array_keys($header)).")
            SET ";
        foreach ($fieldsToFileHeaderPos as $position => $field)
            if (array_key_exists($field, self::fieldFilters()))
                $query .= " $field=".self::fieldFilter($field, "@col".($position+2)).", ";
            else
                $query .= " $field=TRIM(@col".($position+2)."), ";
        $query = rtrim($query, ", ");
        $query .= ", created_at = now(), updated_at = now(), company_id = ".$rpf->company_id.", royalty_payment_file_id = ".$rpf->id;
        DB::connection()->getpdo()->exec($query);
    }

    private static function fieldsToFileHeaderPositionsOld()
    {
        return array_diff(RoyaltyPayment::fieldsToFileHeaderPositions(), array("imported_production_episode_code",
            "exploitation_source_name", "reference"));
    }
    private static function fieldsToFileHeaderPositions()
    {
        return [
            "payee_name",
            "payee_code",
            "client_name",
            "client_code",
            "song_title",
            "song_code",
            "composers",
            "source_name",
            "source_code",
            "income_type_description",
            "income_type",
            "percent_received",
            "amount_received",
            "share",
            "participant_percent",
            "contractual_rate",
            "contractual_code",
            "effective_rate",
            "amount_earned",
            "catalogue_number",
            "units",
            "price",
            "date_period_from",
            "date_period_to",
            "territory_name",
            "territory_code",
            "production_episode",
            "production_episode_code",
            "imported_production_episode_code",
            "exploitation_source_name",
            "notes",
            "currency",
            "statement_id",
            "statement_line",
            "reference",
            "distribution_no",
        ];
    }

    private static function fieldFilters()
    {
        return [
            "date_period_from" => "STR_TO_DATE(%VALUE%, '%M-%Y' )",
            "date_period_to"   => "STR_TO_DATE(%VALUE%, '%M-%Y' )",
        ];
    }

    private static function fieldFilter($field, $value)
    {
        return str_replace("%VALUE%", $value, self::fieldFilters()[$field]);
    }

    public static function cleanEmptyPayments()
    {
        RoyaltyPayment::where("payee_code", "=", 0)
            ->where("client_code", "=", 0)
            ->where("amount_received", "=", 0)
            ->where("amount_earned", "=", 0)
            ->delete();
    }
}