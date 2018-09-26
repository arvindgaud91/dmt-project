<?php

class DmtWalletAction extends \Eloquent {
	protected $guarded = [];

	public function credit ()
	{
		return $this->belongsTo('WalletTransaction', 'credit_id');
	}

	public function debit ()
	{
		return $this->belongsTo('WalletTransaction', 'debit_id');
	}
}

?>