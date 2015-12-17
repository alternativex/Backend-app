<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablesDropPlural extends Migration {

    public function up()
    {
        DB::unprepared("RENAME TABLE `authorization_tokens` TO `authorization_token`;
        RENAME TABLE `deals` TO `deal`;
        RENAME TABLE `password_reminders` TO `password_reminder`;
        RENAME TABLE `royalty_providers` TO `royalty_provider`;
        RENAME TABLE `royalty_shares` TO `royalty_share`;
        RENAME TABLE `royalty_stream_files` TO `royalty_stream_file`;
        RENAME TABLE `royalty_streams` TO `royalty_stream`;
        RENAME TABLE `royalty_types` TO `royalty_type`;
        RENAME TABLE `users` TO `user`;");
    }

	public function down()
	{
	}

}
