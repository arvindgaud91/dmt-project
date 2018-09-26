<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout ()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	protected function setData ($arr, $obj)
  {
		foreach ($arr as $key => $value) {
			$obj->{$key} = $value;
		}
		return $obj;
  }

	/**
	 * Filter an associative array
	 * @param $data Associative array of the data to be filtered
	 * @param $allowedKeys Array of keys
	 * @return Associative array
	 */
	protected function filterOnly ($data, $allowedKeys)
	{
		$finalResult = [];
		foreach ($data as $key => $value) {
			if (in_array($key, $allowedKeys)) $finalResult[$key] = $value;
		}
		return $finalResult;
	}

}
