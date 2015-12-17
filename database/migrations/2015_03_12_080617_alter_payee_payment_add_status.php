<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPayeePaymentAddStatus extends Migration {

	public function up()
	{
		DB::unprepared("ALTER TABLE `payee_payment` ADD `status` ENUM( 'unpaid', 'paid' ) NOT NULL;");
        DB::unprepared("ALTER TABLE `payee_payment` ADD `payee_code` INT( 11 ) NOT NULL , ADD `company_id` INT( 11 ) NOT NULL");
        DB::unprepared("ALTER TABLE `payee_payment` CHANGE `payment_date` `payment_date` DATE NULL");
	}

	public function down()
	{
		//
	}
}
