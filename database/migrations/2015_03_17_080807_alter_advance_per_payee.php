<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAdvancePerPayee extends Migration
{
	public function up()
	{
        DB::unprepared("ALTER TABLE `advance` CHANGE `payee_payment_id` `payee_code` INT( 11 ) NOT NULL");
        DB::unprepared("update `advance` as a set a.payee_code = (select pp.payee_code from payee_payment as pp where id = a.payee_code)");
	}

	public function down()
	{
		//
	}
}
