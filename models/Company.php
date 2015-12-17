<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\UserTrait;

class Company extends ApiModel
{
    protected $table = 'company';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public static function boot()
    {
        parent::boot();
        Company::created(function ($company) {
            AuthorizationToken::createAuthorizationToken($company);
            Credit::createCredit($company->id);
            Credit::createCredit($company->id, Credit::PAID);
        });
    }

    protected function accessors()
    {
        return [
            "authorization_token" => $this->authorizationToken()->first()->token,
            "free_credit" => $this->freeCredit(),
            "paid_credit" => $this->paidCredit(),
            "company_services" => $this->companyServices()
        ];
    }

    public function companyServices()
    {
        return $this->hasMany('CompanyService','company_id','id')->where('company_id', '=', $this->id)->get();
    }

    public function freeCredit()
    {
        return $this->credit(Credit::FREE)->first();
    }

    public function paidCredit()
    {
        return $this->credit(Credit::PAID)->first();
    }

    public function credit($type)
    {
        return $this->hasOne('Credit','company_id','id')->where('type', '=', $type);
    }

    public static function hasCredits($companyId)
    {
        $company = Company::find($companyId);
        return ($company->freeCredit()->quantity > 0 || $company->paidCredit()->quantity > 0);
    }

    public static function decrementCredits($companyId)
    {
        $company = Company::find($companyId);
        $creditDecremented = $company->checkAndDecrementCredit($company->freeCredit());
        if (!$creditDecremented)
            $creditDecremented = $company->checkAndDecrementCredit($company->paidCredit());
        return $creditDecremented;
    }

    public function checkAndDecrementCredit($credit)
    {
        if ($credit->quantity > 0) {
            $this->decrementCredit($credit);
            return true;
        }
        return false;
    }

    public function decrementCredit($credit)
    {
        $credit->quantity--;
        $credit->save();
    }

    public function authorizationToken()
    {
        return $this->hasOne('AuthorizationToken','model_id','id')->where('model', '=', "Company");
    }

    public static function apiAll()
    {
        if (!Auth::user()->isAdmin())
            return [Company::find(Auth::user()["company_id"])];
        else
            return Company::all();
    }
}