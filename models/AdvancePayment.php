<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\UserTrait;

class AdvancePayment extends ApiModel
{
    protected $table = 'advance_payment';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function payeePayment()
    {
        return $this->belongsTo('PayeePayment', 'payee_payment_id','id');
    }
}