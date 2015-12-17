<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDealAddArchived extends Migration {

    public function up()
    {
        DB::unprepared("ALTER TABLE `deals` CHANGE `status` `status` ENUM( 'unreviewed', 'reviewed', 'accepted', 'rejected', 'pass', 'lost', 'contacted', 'archive' ) NOT NULL ");
    }

    public function down()
    {
        //
    }

}
