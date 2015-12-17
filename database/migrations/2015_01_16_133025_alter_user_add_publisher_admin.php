<?php

use Illuminate\Database\Migrations\Migration;

class AlterUserAddPublisherAdmin extends Migration {

    public function up()
    {
        DB::unprepared("ALTER TABLE `users` CHANGE `type` `type` ENUM( 'admin', 'publisher_admin', 'publisher' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ;");
    }

    public function down()
    {
        //
    }

}
