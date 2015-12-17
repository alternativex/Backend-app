<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDealChangePublisherToCompany extends Migration {

	public function up()
	{
        DB::unprepared("ALTER TABLE `deal` CHANGE `publisher_id` `company_id` INT( 10 ) UNSIGNED NOT NULL;");
	}

	public function down()
	{
		//
	}

}
