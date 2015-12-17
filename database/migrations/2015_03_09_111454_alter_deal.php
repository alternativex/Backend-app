<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDeal extends Migration
{
    public function up()
    {
        DB::unprepared("ALTER TABLE `deal` CHANGE `created_at` `created` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `updated_at` `updated` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00';");
        DB::unprepared("ALTER TABLE `deal` ADD `deleted` TINYINT( 1 ) NOT NULL;");
        DB::unprepared("UPDATE `deal` SET `deleted` =1 WHERE `deleted_at` IS NOT NULL;");
    }

    public function down()
    {
        //
    }
}
