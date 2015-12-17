<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPayeePaymentToStatement extends Migration {

	public function up()
	{
        DB::unprepared("ALTER TABLE `payee_payment` ADD `client_code` INT( 11 ) NOT NULL;");
        DB::unprepared("UPDATE `payee_payment` SET `client_code` = `payee_code`");
	}

	public function down()
	{
		//
	}

}
