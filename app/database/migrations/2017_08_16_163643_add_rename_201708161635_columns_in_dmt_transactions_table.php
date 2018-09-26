<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddRename201708161635ColumnsInDmtTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dmt_transactions', function(Blueprint $table)
		{
			$table->renameColumn('request_id', 'bank_transaction_id');
			$table->renameColumn('bank_response_code', 'bank_reference_number');
			$table->renameColumn('result_code', 'npci_result_code');
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
			$table->renameColumn('bank_transaction_id', 'request_id');
			$table->renameColumn('bank_reference_number', 'bank_response_code');
			$table->renameColumn('npci_result_code', 'result_code');
		});
	}

}
