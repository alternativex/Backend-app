<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDealDropCompanyPayee extends Migration {

	public function up()
	{
        DB::unprepared("ALTER TABLE `deal` CHANGE `company_id` `company_id` INT( 11 ) NULL ;");
        DB::unprepared("ALTER TABLE `deal` ADD `payment_analysis` TINYINT NOT NULL");
        DB::unprepared("UPDATE `deal` SET `payment_analysis` = 1 WHERE `payee_code` IS NOT NULL");
        DB::unprepared("ALTER TABLE deal DROP INDEX payee_code");
	}

	public function down()
	{
		//
	}
}
