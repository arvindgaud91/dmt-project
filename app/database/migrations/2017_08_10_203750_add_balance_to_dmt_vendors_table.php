<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddBalanceToDmtVendorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dmt_vendors', function(Blueprint $table)
		{
			$table->decimal('balance');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('dmt_vendors', function(Blueprint $table)
		{
			$table->dropColumn('balance');
		});
	}

}
