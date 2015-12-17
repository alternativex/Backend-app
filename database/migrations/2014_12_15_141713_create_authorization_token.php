<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorizationToken extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('authorization_token', function(Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->dateTime('expirationDate');
            $table->string('model');
            $table->integer('model_id');
            $table->timestamps();
            $table->softDeletes();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('authorization_token');
	}

}
