<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoyaltyStreamsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('royalty_streams', function(Blueprint $table) {
            $table->increments('id');

            $table->boolean('active')->nullable();
            $table->unsignedInteger('stream_file_id')->index()->nullable();
            $table->unsignedInteger('royalty_schedule_id')->index()->nullable();
            $table->unsignedInteger('royalty_item_variation_id')->index()->nullable();
            $table->string('royalty_country_iso', 2)->nullable();
            $table->string('royalty_base_currency', 3)->nullable();
            $table->string('royalty_currency', 3)->nullable();
            $table->double('royalty_amount_base', 10, 2)->nullable();
            $table->unsignedInteger('royalty_match_id')->default(0)->index()->nullable();
            $table->string('song_number')->nullable();
            $table->string('song_title')->index()->nullable();
            $table->string('album_number')->nullable();
            $table->string('album_title')->index()->nullable();
            $table->decimal('royalty_amount', 20, 6)->nullable();
            $table->double('exchange_rate', 10, 2)->nullable();
            $table->smallInteger('album')->default(0)->nullable();
            $table->string('party_id', 20)->nullable();
            $table->string('party_name')->nullable();
            $table->string('performance_source', 200)->index()->nullable();
            $table->string('serial_or_film', 200)->index()->nullable();
            $table->string('region', 200)->index()->nullable();
            $table->string('society_name', 200)->nullable();
            $table->unsignedInteger('number_of_plays')->nullable();
            $table->dateTime('statement_period_from')->nullable();
            $table->dateTime('statement_period_to')->nullable();
            $table->unsignedInteger('royalty_item_id')->nullable();
            $table->string('file_name', 2000)->nullable();
            $table->timestamp('load_date')->nullable();
            $table->boolean('processing_status')->default(0)->nullable();
            $table->string('episode_name')->nullable();
            $table->unsignedInteger('row_data_crt')->nullable();
            $table->unsignedInteger('publisher_id')->index();
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
        Schema::drop('royalty_streams');
    }

}
