<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPaymentStatusToDmtTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dmt_transactions', function(Blueprint $table)
		{
			$table->tinyInteger('payment_status');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('dmt_transactions', function(Blueprint $table)
		{
			$table->dropColumn('payment_status');
		});
	}

}
