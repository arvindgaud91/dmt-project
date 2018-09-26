<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWalletActionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('wallet_actions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index();
			$table->integer('counterpart_id')->unsigned()->index();
			$table->double('amount');
			$table->string('remarks', 1000);
			$table->tinyInteger('status')->default(0);
			$table->integer('debit_id')->unsigned()->index();
			$table->integer('credit_id')->unsigned()->index();
			$table->integer('wallet_request_id')->unsigned()->nullable();
	      	$table->tinyInteger('type');
	      	$table->boolean('admin')->default(false);
	      	$table->boolean('automatic')->default(false);
	      	$table->integer('admin_id')->unsigned()->index();
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
		Schema::drop('wallet_actions');
	}

}
