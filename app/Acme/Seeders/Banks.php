<?php
namespace Acme\Seeders;

class Banks
{
	public static function loadFile ()
	{
		return $results = \Excel::load(app_path().'/Acme/Seeders/banks.csv');
	}
}