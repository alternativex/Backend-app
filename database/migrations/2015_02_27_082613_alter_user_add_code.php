<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserAddCode extends Migration {

    public function up()
    {
        DB::unprepared("ALTER TABLE `user` ADD `code` INT( 11 ) NULL ");
    }

    public function down()
    {
    }

}
