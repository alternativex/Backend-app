<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditTable extends Migration {

    public function up()
    {
        DB::unprepared("CREATE TABLE IF NOT EXISTS `credit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `company_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `type` enum('free','paid') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB ;");
    }

    public function down()
    {
        //
    }

}
