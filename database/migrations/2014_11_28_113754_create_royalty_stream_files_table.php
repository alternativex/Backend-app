<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoyaltyStreamFilesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('royalty_stream_files', function(Blueprint $table) {
            $table->increments('id');

            $table->boolean('active')->nullable();
            $table->unsignedInteger('deal_id')->index()->nullable();
            $table->string('stream_file_name')->index()->nullable();
            $table->unsignedInteger('royalty_provider_id')->index()->nullable();
            $table->unsignedInteger('royalty_type_id')->index()->nullable();
            $table->unsignedInteger('royalty_share_id')->index()->nullable();
            $table->unsignedInteger('percent')->default(100);
            $table->unsignedInteger('error_code')->nullable();
            $table->string('error_description')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->smallInteger('status');
            $table->smallInteger('hystorical_data')->default(1);
            $table->smallInteger('period');
            $table->unsignedInteger('statement_type');
            $table->unsignedInteger('statement_party_id');
            $table->unsignedInteger('period_year')->index()->nullable();
            $table->unsignedInteger('period_month')->index()->nullable();
            $table->unsignedInteger('period_quarter')->index()->nullable();
            $table->unsignedInteger('royalty_payment_id')->nullable();
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
        Schema::drop('royalty_stream_files');
    }

}
