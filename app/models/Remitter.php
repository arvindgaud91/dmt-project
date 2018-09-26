<?php

class Remitter extends \Eloquent {
	protected $guarded = [];

	public function beneficiaries ()
	{
		return $this->hasMany('RemitterBeneficiary');
	}

}

?>
