<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRoyaltyPaymentAddParticipantPercent extends Migration {

	public function up()
	{
        DB::unprepared("ALTER TABLE `royalty_payment` ADD `participant_percent` DECIMAL( 5, 2 ) NOT NULL ;");
	}

	public function down()
	{
		//
	}
}
