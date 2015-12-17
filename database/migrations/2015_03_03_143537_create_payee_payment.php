<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayeePayment extends Migration {

    public function up()
    {
        DB::unprepared("CREATE TABLE IF NOT EXISTS `payee_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `amount` decimal(15,6) NOT NULL,
  `payment_date` date NOT NULL,
  `notes` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB ;");
    }

    public function down()
    {
    }
}
