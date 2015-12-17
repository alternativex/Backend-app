<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoyaltySharesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('royalty_shares', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('short_name')->nullable();
            $table->string('symbol')->nullable();
            $table->softDeletes();
            $table->timestamps();
//            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('royalty_shares');
    }

}
