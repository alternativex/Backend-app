<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoyaltyProvidersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('royalty_providers', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('etl_upload_location');
            $table->string('etl_command');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('royalty_providers');
    }

}
