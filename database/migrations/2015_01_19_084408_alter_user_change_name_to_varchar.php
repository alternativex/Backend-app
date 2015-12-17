<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserChangeNameToVarchar extends Migration {

    public function up()
    {
        DB::unprepared("ALTER TABLE `users` CHANGE `name` `name` VARCHAR( 255 ) NOT NULL ;");
    }

    public function down()
    {
        //
    }

}
