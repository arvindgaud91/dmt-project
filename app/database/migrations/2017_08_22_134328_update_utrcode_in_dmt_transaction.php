<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUtrcodeInDmtTransaction extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dmt_transactions', function(Blueprint $table)
		{
			DB::statement('ALTER TABLE `dmt_transactions` MODIFY `utr_code` VARCHAR(100) NULL;');
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
			DB::statement('ALTER TABLE `dmt_transactions` MODIFY `utr_code` VARCHAR(100) NULL;');
		});
	}

}
