<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateErrorDeleteBeneficiaryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('error_deletebeneficiary', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('description');
			$table->string('status');
			$table->string('beneficiaryid');
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
		Schema::drop('error_deletebeneficiary');
	}

}
