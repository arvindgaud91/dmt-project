<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNpciResultCodeInDmtTransaction extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dmt_transactions', function(Blueprint $table)
		{
			DB::statement('ALTER TABLE `dmt_transactions` MODIFY `npci_result_code` VARCHAR(10) NULL;');
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
			DB::statement('ALTER TABLE `dmt_transactions` MODIFY `npci_result_code` VARCHAR(10) NULL;');
		});
	}

}
