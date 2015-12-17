<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPayeePaymentsAddYearMonthQuarter extends Migration {

	public function up()
	{
        DB::unprepared("ALTER TABLE `payee_payment` ADD `year` INT( 4 ) NULL , ADD `quarter` INT( 1 ) NULL , ADD `month` INT( 2 ) NULL");
	}

	public function down()
	{
	}
}
