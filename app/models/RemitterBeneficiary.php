<?php

class RemitterBeneficiary extends \Eloquent {
	protected $guarded = [];

	public function dmtBankBranch ()
	{
		return $this->belongsTo('DmtBankBranch', 'bank_branch_id');
	}


	
}

?>
