<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserSetNullEmail extends Migration
{
    public function up()
    {
        DB::unprepared("ALTER TABLE `user` CHANGE `email` `email` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;");
    }

    public function down()
    {
    }
}
