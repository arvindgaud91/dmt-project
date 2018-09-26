<?php

class DmtBankBranch extends \Eloquent {
	protected $guarded = [];
	protected $table = "dmt_bank_branches";

	public function dmtBank()
	{
		return $this->belongsTo('DmtBank', 'dmt_bank_id');
	}
}

?>