<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateErrorBeneficiaryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('error_beneficiary', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('description');
			$table->string('status');
			$table->string('user_name');
			$table->string('account_no');
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
		Schema::drop('error_beneficiary');
	}

}
