<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDmtBankBranchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dmt_bank_branches', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('dmt_bank_id')->unsigned();
			$table->string('ifsc', 20);
			$table->string('branch');
			$table->string('address');
			$table->string('city');
			$table->string('state');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('dmt_bank_branches');
	}

}
