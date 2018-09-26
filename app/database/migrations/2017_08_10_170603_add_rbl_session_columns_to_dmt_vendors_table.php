<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddRblSessionColumnsToDmtVendorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dmt_vendors', function(Blueprint $table)
		{
			$table->string('session_token');
			$table->dateTime('timeout');
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
			$table->dropColumn('session_token');
			$table->dropColumn('timeout');
		});
	}

}
