<?php
namespace Acme\Exceptions;
use \Exception;

/**
* 
*/
class GateKeeperException extends Exception
{
	public function __construct($message, $code)
	{
		$this->message = $message;
		$this->code = $code;
	}
}