<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRemittersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('remitters', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->string('rbl_remitter_code', 20);
			$table->string('name');
			$table->string('phone_no', 20);
			$table->string('pincode', 10);
			$table->decimal('limit');
			$table->decimal('consumed_limit');
			$table->boolean('status');
			$table->boolean('validated');
			$table->boolean('account_validated');
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
		Schema::drop('remitters');
	}

}
