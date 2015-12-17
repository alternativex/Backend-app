<?php

use Illuminate\Database\Migrations\Migration;

class CreateServices extends Migration
{

    public function up()
    {
        DB::unprepared("CREATE TABLE IF NOT EXISTS `service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB ;");

        DB::unprepared("INSERT INTO `service` (`id`, `created_at`, `updated_at`, `name`) VALUES
(1, '2015-02-26 14:24:20', '2015-02-26 14:24:20', 'ANALYSIS'),
(2, '2015-02-26 14:24:31', '2015-02-26 14:24:31', 'ROYALTY_PAYMENTS');");
    }

    public function down()
    {
    }

}
