<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRoyaltyStreamAddCompanyPayee extends Migration
{
	public function up()
	{
        DB::unprepared("ALTER TABLE `royalty_stream` ADD `company_name` VARCHAR( 255 ) NULL ,
ADD `company_code` VARCHAR( 255 ) NULL ,
ADD `payee_name` VARCHAR( 255 ) NULL ,
ADD `payee_code` INT( 11 ) NULL ;");
	}

	public function down()
	{
		//
	}
}
