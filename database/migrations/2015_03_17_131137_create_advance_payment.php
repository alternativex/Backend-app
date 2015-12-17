<?php

use Illuminate\Database\Migrations\Migration;

class CreateAdvancePayment extends Migration
{
    public function up()
    {
        DB::unprepared("CREATE TABLE IF NOT EXISTS `advance_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `amount` decimal(15,6) NOT NULL,
  `advance_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB ;");
    }

    public function down()
    {
        //
    }
}
