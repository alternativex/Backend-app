<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class RoyaltyShare extends ApiModel
{
    use SoftDeletingTrait;

    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';

    protected $guarded = [RoyaltyShare::CREATED_AT, RoyaltyShare::UPDATED_AT];
    protected $table = 'royalty_share';

    public static function boot()
    {
        parent::boot();
        RoyaltyShare::deleting(function ($royaltyShare) {
            $royaltyShare->deleted = 1;
            $royaltyShare->update();
        });
    }
}
