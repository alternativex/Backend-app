<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\UserTrait;

class PayeeCompany extends ApiModel implements ApiQueryableInterface
{
    protected $table = 'payee_company';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public static function findByUserAndCompany($userId, $companyId)
    {
        return PayeeCompany::where("user_id", "=", $userId)->where("company_id", "=", $companyId)->first();
    }

    protected function accessors() {
        return [
            "paid_statements_count" => $this->paidStatementsCount(),
            "unpaid_statements_count" => $this->unpaidStatementsCount(),
            "unattached_payments_count" => $this->unattachedPaymentsCount(),
            "attached_payments_count" => $this->attachedPaymentsCount(),
        ];
    }

    public function paidStatementsCount()
    {
        return PayeePayment::paidWherePayeeCode($this->code)->count();
    }

    public function unpaidStatementsCount()
    {
        return PayeePayment::unpaidWherePayeeCode($this->code)->count();
    }

    public function unattachedPaymentsCount()
    {
        return RoyaltyPayment::unattachedPaymentsPerPayee($this->code)->count();
    }

    public function attachedPaymentsCount()
    {
        return RoyaltyPayment::attachedPaymentsPerPayee($this->code)->count();
    }

    public function user()
    {
        return $this->hasOne('User', 'user_id','id');
    }

    public function company() {
        return $this->hasOne('Company','id','company_id');
    }

    public function advances(){
        return $this->hasMany('Advance','payee_code','code')->where("status", "=", "incomplete")->
            where("company_id", "=", Auth::user()["company_id"])->orderBy('start_date');
    }

    public static function payeesWithoutEmail()
    {
        return self::apiQuery()->whereNull("email");
    }


    public static function payeesWithEmail()
    {
        return self::apiQuery()->whereNotNull("email");
    }

    public static function apiQuery() {
        $query = self::query();
        if (!Auth::user()->isAdmin()) {
            $query->where(PayeeCompany::table().'.company_id', '=', Auth::user()["company_id"]);
        }
        $query->join(User::table()." as user", 'user.id', '=', 'user_id');
        return $query;
    }
}