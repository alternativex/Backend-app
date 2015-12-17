<?php

use Illuminate\Database\Migrations\Migration;

class AlterAuthorizationTokenToTokens extends Migration {

    public function up()
    {
        DB::unprepared(" RENAME TABLE `authorization_token` TO `authorization_tokens` ;");
    }

    public function down()
    {
        //
    }

}
