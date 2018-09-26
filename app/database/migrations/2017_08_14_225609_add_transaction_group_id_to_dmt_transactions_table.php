<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddTransactionGroupIdToDmtTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dmt_transactions', function(Blueprint $table)
		{
			$table->string('transaction_group_id', 6);
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
			$table->dropColumn('transaction_group_id');
		});
	}

}
