<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayeeCompany extends Migration {

	public function up()
	{
        DB::unprepared("CREATE TABLE `payee_company` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`created_at` DATETIME NOT NULL,
	`updated_at` DATETIME NOT NULL,
	`user_id` INT(10) UNSIGNED NOT NULL,
	`code` INT(11) NOT NULL,
	`company_id` INT(11) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `user_id_company_id` (`user_id`, `company_id`),
	INDEX `pc_code` (`code`),
	INDEX `pc_company_id` (`company_id`),
	CONSTRAINT `pc_company_id` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT `pc_code` FOREIGN KEY (`code`) REFERENCES `user` (`code`) ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT `pc_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
ENGINE=InnoDB
;");
	}

	public function down()
	{
		//
	}

}
