<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRoyaltyPaymentAddPayeePaymentId extends Migration
{
    public function up()
    {
        DB::unprepared("ALTER TABLE `royalty_payment` ADD `payee_payment_id` INT( 11 ) NULL AFTER `royalty_payment_file_id`;");
    }

    public function down()
    {
    }
}
