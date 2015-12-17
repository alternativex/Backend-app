<?php

class Client extends ApiModel
{
    protected $table = 'client';
    protected $guarded = ['id', 'created_at', 'updated_at'];

}