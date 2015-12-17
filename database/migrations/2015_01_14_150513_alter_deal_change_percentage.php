<?php

use Illuminate\Database\Migrations\Migration;

class AlterDealChangePercentage extends Migration {

    public function up()
    {
        DB::unprepared("ALTER TABLE `deals` CHANGE `percentage` `percentage` INT( 3 ) NOT NULL DEFAULT '100';");
    }

    public function down()
    {
        //
    }

}
