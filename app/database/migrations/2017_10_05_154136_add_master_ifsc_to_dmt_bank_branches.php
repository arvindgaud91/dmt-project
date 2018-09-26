<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMasterIfscToDmtBankBranches extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dmt_bank_branches', function(Blueprint $table)
		{
				$table->tinyInteger('master_ifsc')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('dmt_bank_branches', function(Blueprint $table)
		{
			$table->dropColumn('master_ifsc');
		});
	}

}
