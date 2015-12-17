<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRoyaltyStream extends Migration
{
    public function up()
    {
        DB::unprepared("ALTER TABLE `royalty_stream` DROP `created`, DROP `updated`;");
        DB::unprepared("ALTER TABLE `royalty_stream` CHANGE `created_at` `created` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `updated_at` `updated` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00'");
        DB::unprepared("ALTER TABLE `royalty_stream` CHANGE `deleted` `deleted` TINYINT( 1 ) NOT NULL ");
        DB::unprepared("UPDATE `royalty_stream` SET deleted =1 WHERE `deleted_at` IS NOT NULL ");
    }

    public function down()
    {
        //
    }
}
