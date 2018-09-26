<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddUtrCodeToDmtTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dmt_transactions', function(Blueprint $table)
		{
			$table->string('utr_code', 50);
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
			$table->dropColumn('utr_code');	
		});
	}

}
