<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\UserTrait;

class Advance extends ApiModel
{
    protected $table = 'advance';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function advancePayments()
    {
        return $this->hasMany('AdvancePayment','advance_id','id');
    }

    public function company()
    {
        return $this->hasMany('Company','id','company_id');
    }

    public function advancePaymentsSum()
    {
        $advancePaymentsSum = $this->advancePayments()->select(DB::raw('sum(amount) AS amount'))->first();
        return ($advancePaymentsSum->amount != null) ? $advancePaymentsSum->amount : 0;
    }

    public function amountLeftToPay()
    {
        return $this->amount - $this->advancePaymentsSum();
    }

    public static function apiQuery() {

        $query = self::query();
        if (Auth::user()->isPublisherAdmin() || Auth::user()->isPublisher())
        {
            $query->where(Advance::table().'.company_id', '=', Auth::user()["company_id"]);
        }

        if (Auth::user()->isPayee())
        {
            $query->where(Advance::table().".payee_code", '=', Auth::user()["code"]);
        }
        return $query;
    }

    protected function accessors() {
        return [
            "company_name" => $this->company()->first()->name,
            "advance_payments_sum" => $this->advancePaymentsSum(),
            "amount_left_to_pay" => $this->amountLeftToPay()
        ];
    }
}