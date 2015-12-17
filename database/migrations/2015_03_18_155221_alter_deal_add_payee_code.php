<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDealAddPayeeCode extends Migration
{
	public function up()
	{
        DB::unprepared("ALTER TABLE `deal` ADD `payee_code` INT( 11 ) NULL ;");
        DB::unprepared("ALTER TABLE `deal` ADD UNIQUE (`payee_code`);");
	}

	public function down()
	{

	}

}
