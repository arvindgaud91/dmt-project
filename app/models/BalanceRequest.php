<?php

class BalanceRequest extends \Eloquent {
	protected $fillable = ['user_id', 'service_id', 'amount', 'transfer_mode', 'bank', 'branch', 'reference_number'];
	protected $table = 'wallet_balance_requests';
}