<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaidPaymentAddMarkAsPaidDate extends Migration
{
	public function up()
	{
        DB::unprepared("ALTER TABLE `payee_payment` ADD `marked_as_paid_at` DATETIME NULL ");
        DB::unprepared("UPDATE `payee_payment` SET marked_as_paid_at = updated_at WHERE `status` = 'paid' AND marked_as_paid_at IS NULL ;");
	}

	public function down()
	{
		//
	}
}
