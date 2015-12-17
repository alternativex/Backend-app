<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvance extends Migration {

	public function up()
	{
        DB::unprepared("
CREATE TABLE IF NOT EXISTS `advance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `payee_payment_id` int(11) NOT NULL,
  `amount` decimal(15,6) NOT NULL,
  `start_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB ;");
	}

	public function down()
	{
	}
}
