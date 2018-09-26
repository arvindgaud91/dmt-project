<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVendorBalanceRequestLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vendor_balance_request_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('request_id')->unsigned()->index();
			$table->integer('user_id')->unsigned()->index();
			$table->tinyInteger('type');
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
		Schema::drop('vendor_balance_request_logs');
	}

}
