<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRoyaltyPaymentFileAddPeriod extends Migration
{
	public function up()
	{
        DB::unprepared("ALTER TABLE `royalty_payment_file` ADD `year` INT( 4 ) NULL , ADD `quarter` INT( 1 ) NULL , ADD `month` INT( 2 ) NULL");
	}
}
