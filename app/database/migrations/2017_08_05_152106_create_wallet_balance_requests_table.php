<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWalletBalanceRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('wallet_balance_requests', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('service_id');
			$table->integer('amount');
			$table->smallInteger('transfer_mode');
			$table->integer('bank');
			$table->string('branch', 150);
			$table->string('reference_number', 50);
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
		Schema::drop('wallet_balance_requests');
	}

}
