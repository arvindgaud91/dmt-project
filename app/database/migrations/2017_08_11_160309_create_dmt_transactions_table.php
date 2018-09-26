<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDmtTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dmt_transactions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index();
			$table->integer('remitter_id')->unsigned()->index();
			$table->integer('beneficiary_id')->unsigned()->index();
			$table->decimal('amount');
			$table->tinyInteger('type');
			$table->tinyInteger('status')->default(0);
			$table->tinyInteger('result')->default(0);
			$table->string('result_code', 10);
			$table->string('reference_number', 50);
			$table->string('bank_response_code', 20);
			$table->string('request_id')->index();
			$table->string('remarks', 255);
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
		Schema::drop('dmt_transactions');
	}

}
