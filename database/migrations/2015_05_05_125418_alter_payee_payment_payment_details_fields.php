<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPayeePaymentPaymentDetailsFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::unprepared("ALTER TABLE `payee_payment` ADD `period_paid` ENUM( '1H14', '2Q14') DEFAULT NULL;");
        DB::unprepared("ALTER TABLE `payee_payment` ADD `amount_paid` DECIMAL(15,6) DEFAULT NULL;");
        DB::unprepared("ALTER TABLE `payee_payment` ADD `payment_type` ENUM( 'CHECK', 'ACH', 'WIRE') DEFAULT NULL;");
        DB::unprepared("ALTER TABLE `payee_payment` ADD `check_number` TINYTEXT DEFAULT NULL;");
        DB::unprepared("ALTER TABLE `payee_payment` ADD `payment_details` ENUM( 'CURRENT PERIOD', 'CURRENT PERIOD AND PRIOR BALANCE', 'ADVANCE') DEFAULT NULL;");
        DB::unprepared("ALTER TABLE `payee_payment` ADD `maestro_vendor_code` TINYTEXT DEFAULT NULL;");


    }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
