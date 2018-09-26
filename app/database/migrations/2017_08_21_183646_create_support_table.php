<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupportTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('support', function(Blueprint $table)
		{
			$table->increments('id');
        $table->string('ticket_id', 50);
        $table->string('user_id', 40);
        $table->string('type');
        $table->text('message');
        $table->string('status');
        $table->text('response');
        $table->timestamps();
        $table->text('response_date');
		});
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //
    }

}
