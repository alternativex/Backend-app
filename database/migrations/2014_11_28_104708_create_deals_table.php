<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('deals', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->enum('status', ['unreviewed', 'reviewed', 'accepted', 'rejected', 'pass', 'lost', 'contacted']);
            $table->enum('etl_status', ['processed', 'error', 'processing']);
            $table->string('writer_name');
            $table->string('writer_email');
            $table->string('writer_phone');
            $table->unsignedInteger('publisher_id');
            $table->float('percentage')->default(100);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('deals');
    }

}
