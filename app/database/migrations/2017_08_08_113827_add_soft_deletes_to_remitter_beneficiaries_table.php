<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddSoftDeletesToRemitterBeneficiariesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('remitter_beneficiaries', function(Blueprint $table)
		{
			$table->softDeletes();	
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
			$table->dropColumn('deleted_at');
		});
	}

}
