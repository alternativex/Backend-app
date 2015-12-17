<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStreamsAddAccountName extends Migration {

	public function up()
	{
        DB::unprepared("ALTER TABLE `royalty_stream` ADD `account_name` VARCHAR( 255 ) NULL ;
        ALTER TABLE `royalty_stream_file` ADD `account_name` VARCHAR( 255 ) NULL ;");
	}

	public function down()
	{
		//
	}

}
