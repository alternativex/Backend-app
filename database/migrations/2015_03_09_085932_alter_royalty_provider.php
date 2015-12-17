<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRoyaltyProvider extends Migration {

	public function up()
	{
        DB::unprepared("UPDATE `royalty_provider` SET `royalty_provider_name` = `name`;");
        DB::unprepared("ALTER TABLE `royalty_provider` DROP `name`;");
        DB::unprepared("ALTER TABLE `royalty_provider` CHANGE `created_at` `created` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00'");
        DB::unprepared("ALTER TABLE `royalty_provider` CHANGE `updated_at` `updated` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00'");
        DB::unprepared("ALTER TABLE `royalty_provider` ADD `deleted_at` TIMESTAMP NULL");
        DB::unprepared("ALTER TABLE `royalty_provider` ADD `deleted` TINYINT( 1 ) NOT NULL");
        DB::unprepared("ALTER TABLE `royalty_provider` ADD `user_id` INT( 11 ) NULL");
	}

	public function down()
	{
		//
	}

}
