<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePincodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pincodes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('pincode', 10);
			$table->string('office_type');
			$table->string('office_name');
			$table->string('postal_region');
			$table->string('region');
			$table->string('city');
			$table->string('state');
			$table->tinyInteger('status');
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
		Schema::drop('pincodes');
	}

}
