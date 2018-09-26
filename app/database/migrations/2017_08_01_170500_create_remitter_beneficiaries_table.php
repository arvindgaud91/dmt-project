<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRemitterBeneficiariesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('remitter_beneficiaries', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('remitter_id')->unsigned();
			$table->string('name');
			$table->integer('bank_branch_id')->unsigned();
			$table->string('account_number');
			$table->string('rbl_beneficiary_code', 20);
			$table->tinyInteger('status');
			$table->boolean('validated');
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
		Schema::drop('sender_receivers');
	}

}
