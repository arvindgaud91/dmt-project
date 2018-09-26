<?php

namespace Acme\SMS;
// use Plivo\RestAPI;
use Log;

/**
 * SMS Class implementing ...
 */
class SMS
{
  public static function send ($to, $content)
  {
    if (! getenv('SEND_SMS')) {
      Log::info('SMS being sent.');
      return;
    }
    $response = \Unirest\Request::post("http://sms.hspsms.com/sendSMS?username=DIPL_2015&message=".$content."&sendername=DIGPAY&smstype=TRANS&numbers=".$to."&apikey=8a841c94-76e8-4a8a-92ae-6bb4cdc04f0b", []);
    \Log::info(json_encode($response));
    return true;
  //   $auth_id = "MAOWIWMGU2YJY5NDFLMT";
		// $auth_token = "ZGYzMTk5ZjMwNzEzZjFiMzE4NzlhNTNlMDIxOWQy";
		// $p = new RestAPI($auth_id, $auth_token);
  //   // Send SMS
  //   $params = [
  //   	'src' => 'SYMFIN',
  //   	'dst' => '91'.$to,
  //   	'text' => $content
		// ];
		// return $response = $p->send_message($params);
  }
}
