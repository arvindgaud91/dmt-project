<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddAdminIdToWalletBalanceRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('wallet_balance_requests', function(Blueprint $table)
		{
			$table->integer('admin_id')->unsigned();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('wallet_balance_requests', function(Blueprint $table)
		{
			$table->dropColumn('admin_id');
		});
	}

}
