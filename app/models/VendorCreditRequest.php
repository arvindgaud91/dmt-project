<?php

class VendorCreditRequest extends \Eloquent {
	protected $fillable = ['user_id', 'child_id', 'amount', 'remarks'];

	public function user ()
	{
		return $this->belongsTo('User');
	}

}
