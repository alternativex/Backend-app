<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\UserTrait;

class Credit extends ApiModel
{
    const FREE = "free";
    const PAID = "paid";

    protected $table = 'credit';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function company()
    {
        return $this->hasOne('Company', 'id', 'company_id');
    }

    public static function createCredit($companyId, $type = self::FREE, $quantity = 10)
    {
        return Credit::create(["company_id" => $companyId, "type" => $type, "quantity" => $quantity]);
    }
}