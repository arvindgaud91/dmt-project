<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRemitterKycDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('remitter_kycdetails', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('remitter_id');
			$table->string('last_name');
			$table->integer('mobile');
			$table->string('mother_name');
			$table->string('father_name');
			$table->string('email_id');
			$table->string('dob');
			$table->string('gender');
			$table->string('education');
			$table->string('religion');
			$table->string('nationality');
			$table->string('category');
			$table->string('marital_status');
			$table->string('residential_status');
			$table->string('pan_no');
			$table->string('add_proof_type');
			$table->string('nominee_name');
			$table->string('relation_nominee');
			$table->string('age_nominee');
			$table->string('dob_nominee');
			$table->string('cust_status');
			$table->string('cust_type');
			$table->string('income_type');
			$table->string('annual_income');
            $table->string('politically_exposed');
			$table->string('kyc_pan_card');
			$table->string('kyc_add_proof');
			$table->string('kyc_req_form');
			$table->integer('isUploaded');
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
		Schema::drop('remitter_kycdetails');
	}

}
