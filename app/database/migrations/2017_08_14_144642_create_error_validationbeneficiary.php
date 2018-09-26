<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErrorValidationbeneficiary extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('error_validationbeneficiary', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('description');
			$table->string('status');
			$table->string('user_name');
			$table->string('account_no');
			$table->string('request');
			$table->string('response');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('error_validationbeneficiary');
	}

}
