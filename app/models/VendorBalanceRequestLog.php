<?php

class VendorBalanceRequestLog extends \Eloquent {
	protected $fillable = ['request_id', 'user_id', 'type', 'status'];
}
