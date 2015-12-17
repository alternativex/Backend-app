<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentFileAddNewStatus extends Migration {

	public function up()
	{
        DB::unprepared("ALTER TABLE `royalty_payment_file` CHANGE `status` `status` ENUM( 'uploaded', 'processed', 'payments_processed' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
	}

	public function down()
	{
	}
}
