<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountValidationToRemitterBeneficiaries extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('remitter_beneficiaries', function(Blueprint $table)
		{
			$table->string('accountValidation', 20);
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
			$table->dropColumn('accountValidation');
		});
	}

}
