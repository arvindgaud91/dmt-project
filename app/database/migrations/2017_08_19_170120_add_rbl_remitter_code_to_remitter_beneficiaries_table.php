<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddRblRemitterCodeToRemitterBeneficiariesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('remitter_beneficiaries', function(Blueprint $table)
		{
			$table->string('rbl_remitter_code', 20);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('remitter_beneficiaries', function(Blueprint $table)
		{
			$table->dropColumn('rbl_remitter_code');
		});
	}

}
