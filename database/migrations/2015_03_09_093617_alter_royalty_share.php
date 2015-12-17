<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRoyaltyShare extends Migration {

    public function up()
    {
        DB::unprepared("UPDATE `royalty_share` SET `royalty_share_name` = `name`;");
        DB::unprepared("ALTER TABLE `royalty_share` DROP `name`;");
        DB::unprepared("ALTER TABLE `royalty_share` ADD `royalty_share_name_short` VARCHAR( 255 ) NULL ,
ADD `royalty_share_name_symbol` VARCHAR( 255 ) NULL;");
        DB::unprepared("UPDATE `royalty_share` SET `royalty_share_name_short` = `short_name` ,
`royalty_share_name_symbol` = `symbol`;");
        DB::unprepared("ALTER TABLE `royalty_share` DROP `short_name`, DROP `symbol`;");
        DB::unprepared("ALTER TABLE `royalty_share` CHANGE `created_at` `created` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00';");
        DB::unprepared("ALTER TABLE `royalty_share` CHANGE `updated_at` `updated` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00'");
        DB::unprepared("ALTER TABLE `royalty_share` ADD `deleted` TINYINT( 1 ) NOT NULL , ADD `updated_id` INT( 11 ) NULL ");
    }

    public function down()
    {
        //
    }
}
