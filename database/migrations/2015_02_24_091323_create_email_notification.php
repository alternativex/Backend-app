<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailNotification extends Migration {

    public function up()
    {
        DB::unprepared("
CREATE TABLE IF NOT EXISTS `email_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `model` varchar(255) NOT NULL,
  `model_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notification_sent` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `model_modelId_type` (`model`,`model_id`,`type`)
) ENGINE=InnoDB ;");
    }

    public function down()
    {
    }
}
