<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterSupportTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('master_support', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->string('support_id', 10);
                        $table->string('support_name', 200);
                        
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
		Schema::table('master_support', function(Blueprint $table)
		{
			//
		});
	}

}
