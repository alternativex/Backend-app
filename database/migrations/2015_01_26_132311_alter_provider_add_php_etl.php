<?php

use Illuminate\Database\Migrations\Migration;

class AlterProviderAddPhpEtl extends Migration {

    public function up()
    {
        DB::unprepared("ALTER TABLE `royalty_provider` ADD `php_etl_command` VARCHAR( 255 ) NOT NULL ,
ADD `php_etl_upload_location` VARCHAR( 255 ) NOT NULL ;
update `royalty_provider` set `php_etl_command` = 'php ~/royaltysnapshot.com/www/etl/main.php publisher',
`php_etl_upload_location` = '/var/home/royalty/royaltysnapshot.com/www/publisher/backend/app/storage/temp'");
    }

    public function down()
    {
        //
    }

}
