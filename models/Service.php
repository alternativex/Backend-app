<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\UserTrait;

class Service extends ApiModel
{
    protected $table = 'service';
    protected $guarded = ['id', 'created_at', 'updated_at'];
}