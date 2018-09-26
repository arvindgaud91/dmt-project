<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Update201708191750ColumnsInRemittersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('remitters', function(Blueprint $table)
		{
			$table->dropColumn('account_validated');
			$table->tinyInteger('kyc_applied');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('remitters', function(Blueprint $table)
		{
			$table->boolean('account_validated');
			$table->dropColumn('kyc_applied');
		});
	}

}
