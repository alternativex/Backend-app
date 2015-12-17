<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToPayment extends Migration
{
	public function up()
	{
        DB::unprepared("ALTER TABLE `advance` ADD `status` ENUM( 'incomplete', 'complete' ) NOT NULL ");
	}

	public function down()
	{
		//
	}
}
