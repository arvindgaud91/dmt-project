<?php

class DevicesController extends BaseController
{
  public function getSelectDevice()
  {
    return View::make('devices.select');
  }
}
