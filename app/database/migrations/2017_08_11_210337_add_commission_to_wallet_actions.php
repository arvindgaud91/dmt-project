<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddCommissionToWalletActions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('wallet_actions', function(Blueprint $table)
		{
			$table->boolean('commission')->default(false);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('wallet_actions', function(Blueprint $table)
		{
			$table->dropColumn('commission');
		});
	}

}
