<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRoyaltyPaymentAddFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::unprepared("ALTER TABLE `royalty_payment`
                            ADD `imported_production_episode_code` INT( 10 ) DEFAULT 0,
                            ADD `exploitation_source_name` VARCHAR(255) DEFAULT NULL,
                            ADD `reference` VARCHAR(255) DEFAULT NULL,
                            ADD `distribution_no` VARCHAR(255) DEFAULT NULL");
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