<?php

class RoyaltyProvider extends ApiModel {

    use SoftDeletingTrait;

    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';

    protected $guarded = [RoyaltyProvider::CREATED_AT, RoyaltyProvider::UPDATED_AT];
    protected $table = 'royalty_provider';

    public static function boot()
    {
        parent::boot();
        RoyaltyProvider::deleting(function ($royaltyProvider) {
            $royaltyProvider->deleted = 1;
            $royaltyProvider->update();
        });
    }
}
