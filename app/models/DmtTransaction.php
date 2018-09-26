<?php

class DmtTransaction extends \Eloquent {
	protected $guarded = ['id'];

	public function remitter ()
	{
		return $this->belongsTo('Remitter');
	}
}

?>