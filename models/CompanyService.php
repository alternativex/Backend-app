<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\UserTrait;

class CompanyService extends ApiModel
{
    protected $table = 'company_service';
    protected $guarded = ['id', 'created_at', 'updated_at'];
}