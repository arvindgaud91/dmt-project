<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKycTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kyc', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('remitter_id');
			$table->integer('mobile_number');
			$table->decimal('consumedLimit');
			$table->decimal('remainingLimit');
			$table->string('kycStatus');
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
		Schema::drop('kyc');
	}

}
