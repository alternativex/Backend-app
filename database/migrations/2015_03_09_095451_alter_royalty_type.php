<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRoyaltyType extends Migration
{
    public function up()
    {
        DB::unprepared("UPDATE `royalty_type` SET `royalty_type_name` = `name` ;");
        DB::unprepared("ALTER TABLE `royalty_type` CHANGE `created_at` `created` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00';");
        DB::unprepared("ALTER TABLE `royalty_type` CHANGE `updated_at` `updated` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00'");
        DB::unprepared("ALTER TABLE `royalty_type` ADD `deleted` TINYINT( 1 ) NOT NULL ");
    }

    public function down()
    {
        //
    }
}
