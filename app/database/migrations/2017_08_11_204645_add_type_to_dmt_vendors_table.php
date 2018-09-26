<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddTypeToDmtVendorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dmt_vendors', function(Blueprint $table)
		{
			$table->tinyInteger('type');
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
			$table->dropColumn('type');
		});
	}

}
