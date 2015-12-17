<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoyaltyPayment extends Migration {

    public function up()
    {
        DB::unprepared("CREATE TABLE IF NOT EXISTS `royalty_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `company_id` int(11) NOT NULL,
  `royalty_payment_file_id` int(11) NOT NULL,
  `payee_name` varchar(255) DEFAULT NULL,
  `payee_code` int(11) DEFAULT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `client_code` int(11) DEFAULT NULL,
  `song_title` varchar(255) DEFAULT NULL,
  `song_code` int(11) DEFAULT NULL,
  `composers` varchar(255) DEFAULT NULL,
  `source_name` varchar(255) DEFAULT NULL,
  `source_code` varchar(255) DEFAULT NULL,
  `income_type_description` varchar(255) DEFAULT NULL,
  `income_type` int(11) DEFAULT NULL,
  `percent_received` int(3) DEFAULT NULL,
  `amount_received` decimal(15,6) DEFAULT NULL,
  `share` int(11) DEFAULT NULL,
  `contractual_rate` int(11) DEFAULT NULL,
  `contractual_code` int(11) DEFAULT NULL,
  `effective_rate` int(11) DEFAULT NULL,
  `amount_earned` decimal(15,6) DEFAULT NULL,
  `catalogue_number` varchar(255) DEFAULT NULL,
  `units` int(11) DEFAULT NULL,
  `price` decimal(15,6) DEFAULT NULL,
  `date_period_from` date DEFAULT NULL,
  `date_period_to` date DEFAULT NULL,
  `territory_name` varchar(255) DEFAULT NULL,
  `territory_code` varchar(255) DEFAULT NULL,
  `production_episode` varchar(255) DEFAULT NULL,
  `production_episode_code` varchar(255) DEFAULT NULL,
  `notes` tinytext,
  `currency` varchar(5) DEFAULT NULL,
  `statement_id` int(11) DEFAULT NULL,
  `statement_line` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB ;");
    }

    public function down()
    {
    }
}
