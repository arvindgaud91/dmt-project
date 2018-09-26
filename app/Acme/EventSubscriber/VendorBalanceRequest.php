<?php

namespace Acme\EventSubscriber;
use Log;
use VendorBalanceRequestLog;

class VendorBalanceRequest {

  public function automatedLog ($data)
  {
    VendorBalanceRequestLog::create($data);
  }
  public function logEntry ($data)
  {
    VendorBalanceRequestLog::create($data);
  }

  public function subscribe ($events)
  {
    $events->listen('vendorBalanceRequest:committed', 'Acme\EventSubscriber\VendorBalanceRequest@logEntry');
  }

}
