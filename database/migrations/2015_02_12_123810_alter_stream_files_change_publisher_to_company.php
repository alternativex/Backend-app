<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStreamFilesChangePublisherToCompany extends Migration {

	public function up()
	{
        DB::unprepared("ALTER TABLE `royalty_stream_file` CHANGE `publisher_id` `company_id` INT( 10 ) UNSIGNED NOT NULL ;");
	}

	public function down()
	{
		//
	}

}
