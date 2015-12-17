<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserAddCompany extends Migration {

	public function up()
	{
        DB::unprepared("ALTER TABLE `user` ADD `company_id` INT( 11 ) NOT NULL ");
	}

    public function down()
	{
		//
	}
}
