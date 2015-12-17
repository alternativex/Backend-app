<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class RoyaltyType extends ApiModel
{
    use SoftDeletingTrait;

    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';

    protected $guarded = [RoyaltyType::CREATED_AT, RoyaltyType::UPDATED_AT];
    protected $table = 'royalty_type';

    public static function boot()
    {
        parent::boot();
        RoyaltyType::deleting(function ($royaltyType) {
            $royaltyType->deleted = 1;
            $royaltyType->update();
        });
    }
}
