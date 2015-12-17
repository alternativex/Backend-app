<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserAddPayee extends Migration {

    public function up()
    {
        DB::unprepared("ALTER TABLE `user` CHANGE `type` `type` ENUM( 'admin', 'publisher_admin', 'publisher', 'payee' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ");
    }

    public function down()
    {
    }
}
