<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountValidation extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('accountValidation', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('referenceNo');
			$table->string('rblreferenceNo');
			$table->string('rblremitterid');
			$table->string('beneficiaryName');
			$table->string('accountNo');
			$table->string('ifsc');
			$table->decimal('amount');
			$table->string('bankRemarks');
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
		Schema::drop('accountValidation');
	}

}
