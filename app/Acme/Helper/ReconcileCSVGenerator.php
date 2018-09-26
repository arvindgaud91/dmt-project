<?php
namespace Acme\Helper;
use Excel;

/**
* 
*/
class ReconcileCSVGenerator
{
	function __construct()
	{
		
	}

	public function convert ($fileLink)
	{
		return Excel::load($fileLink);
	}
}