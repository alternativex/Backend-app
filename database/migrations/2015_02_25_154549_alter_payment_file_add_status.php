<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentFileAddStatus extends Migration {

    public function up()
    {
        DB::unprepared("ALTER TABLE `royalty_payment_file` ADD `status` ENUM( 'uploaded', 'processed' ) NOT NULL ;");
    }

    public function down()
    {
    }

}
