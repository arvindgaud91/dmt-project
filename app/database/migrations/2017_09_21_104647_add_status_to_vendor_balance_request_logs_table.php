<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddStatusToVendorBalanceRequestLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('vendor_balance_request_logs', function(Blueprint $table)
		{
				$table->tinyInteger('status');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('vendor_balance_request_logs', function(Blueprint $table)
		{
			$table->dropColumn('status');
		});
	}

}
