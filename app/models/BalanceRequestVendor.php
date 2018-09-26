<?php

class BalanceRequestVendor extends \Eloquent {
	protected $fillable = ['user_id', 'parent_id', 'amount', 'remarks'];
	protected $table = 'wallet_balance_vendor_requests';

}
