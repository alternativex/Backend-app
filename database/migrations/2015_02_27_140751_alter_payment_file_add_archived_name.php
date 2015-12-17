<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentFileAddArchivedName extends Migration {

    public function up()
    {
        DB::unprepared("ALTER TABLE `royalty_payment_file` ADD `archived_path` VARCHAR( 255 ) NULL  ");
    }

    public function down()
    {
    }
}
