<?php
use Acme\Auth\Auth;
use Acme\SMS\SMS;

class MobileApiUserController extends BaseController
{
	public function postLoginAgent ()
	{
		$credentials = ['phone_no' => Input::get('uid', ''), 'password' => Input::get('password', '')];
		$user = Auth::validate($credentials);
	  if (! $user)
	  {
	    return Response::json('Username or password is invalid', 400);
	  }

	  if ($user->phone_verified == 0) {
	    return Response::json('Please verify the mobile number', 401);
	  }
	  if ($user->status == 0) {
	    return Response::json('Username or password is invalid', 400);
	  }

	  $vendor = Vendor::where('user_id', $user->id)->first();
	  $rblResponse = $this->loginRBL($vendor);
	  if (! $rblResponse || $rblResponse->responseCode == '01')
	  {
	    // \Log::info(json_decode($rblResponse));
	    // @todo set the reason from bank
	    return Response::json('', 403);
	  }
	  // \Log::info(json_encode($rblResponse));

	  $vendor->freshness_factor = $rblResponse->nextFreshnessFactor;
	  $vendor->save();

	  // Event::fire('user.login', ['user' => Auth::user(), 'vendor' => $vendor, 'rblResponse' => $rblResponse]);
	  return Response::json('success', 200)->header('auth', Auth::generateLoginToken($user));
	}

	public function postLoginDistributor ()
	{
		$credentials = ['phone_no' => Input::get('uid', ''), 'password' => Input::get('password', '')];
		$user = Auth::validate($credentials);
	  if (! $user)
	  {
	    return Response::json('Username or password is invalid', 400);
	  }

	  if ($user->phone_verified == 0) {
	    return Response::json('Please verify the mobile number', 401);
	  }
	  if ($user->status == 0) {
	    return Response::json('Username or password is invalid', 400);
	  }
	  // Event::fire('user.login', ['user' => Auth::user(), 'vendor' => $vendor, 'rblResponse' => $rblResponse]);
	  return Response::json('success', 200)->header('auth', Auth::generateLoginToken($user));
	}

	public function postForgotPassword ()
	{
		// $user = User::whereEmail(Input::get('email', ''))->first();
		// if (! $user) {
		// 	return Response::json('Email Id not registered with us', 400);
		// }
  //   $token = md5(time().'_'.$user->id);
  //   $pwdToken = PasswordResetToken::create([
  //     'user_id' => $user->id,
  //     'token' => $token,
  //     'ip' => Request::getClientIp()
  //   ]);
  //   Event::fire('user.password.reset-request', [$pwdToken]);
		// return Response::json('', 200);

		$user = User::where('phone_no', Input::get('phone_no', ''))
			->where('type', 4)
			->first();
		if (! $user) {
			return Response::json('Phone number not registered with us', 400);
		}
		$otp = mt_rand(1000, 9999);
		$otpObj = new PasswordResetOTP(['user_id' => $user->id, 'otp' => $otp.'', 'ip' => Request::getClientIp()]);
		$otpObj->save();
		SMS::send($user->phone_no, 'Yor OTP is '.$otp.'. Digital India Payments.');
		return Response::json('', 200);
	}

	public function postForgotPasswordNewPassword ()
	{
		if (! Input::has('phone_no') || ! Input::has('otp') || ! Input::has('password') || ! Input::has('password_confirmation')) {
			return Response::json('Missing info', 400);
		}
		$user = User::where('phone_no', Input::get('phone_no'))->first();
		if (! $user) return Response::json('Phone number not registered with us', 400);
		$status = PasswordResetOTP::getOTP(Input::get('otp'), $user->id);
		if (! $status) return Response::json('Invalid OTP or expired OTP', 400);
		$p = Hash::make(Input::get('password'));
		// $user->password = $p;
		// return Response::json($user->password, 500);
		// $user->save();
		User::where('phone_no', Input::get('phone_no'))->update(['password' => $p]);

		return Response::json('', 200);
	}

	public function postResendOTP ()
	{
		if (! Input::has('phone_no')) return Response::json('Data is missing', 400);
		$user = User::where('phone_no', Input::get('phone_no'))->first();
		if (! $user) return Response::json('Phone number not registered with us', 400);


		$pwdToken = PasswordResetOTP::where('user_id', $user->id)
      ->orderBy('created_at', 'DESC')
      ->first();
    if ($pwdToken && ((new DateTime)->getTimestamp() - $pwdToken->created_at->getTimestamp()) < 50 * 60) {
    } else {
    	$otp = mt_rand(1000, 9999);
    	$pwdToken = PasswordResetOTP::create(['user_id' => $user->id, 'otp' => $otp.'', 'ip' => Request::getClientIp()]);
    }
		SMS::send($user->phone_no, 'Yor OTP is '.$pwdToken->otp.'. Digital India Payments.');
		return Response::json('', 200);
	}

	public function getProfile ()
	{
		$user = Auth::user();
		if (! $user) {
			return Response::json('In valid token', 444);
		}
		return Response::json([
			// @todo confirm with amol
			"id" => $user->id,
			"name" => $user->name,
			"userName" => $user->name,
			"email" => $user->email,
			"mobileNumber" => $user->phone_no,
			// @todo confirm
			"image" => '',
			"status" => $user->status,
			"balance" => $user->vendorDetails->balance,
			"joiningDate" => $user->created_at->format("d-m-Y"),
			// @todo confirm with amol again
			"dipId" => $user->vendorDetails->csr_id,
			"terminalId" => $user->vendorDetails->terminal_id,
		], 200);
	}

	public function postChangePassword ()
	{
		$user = Auth::user();
		if (! $user) {
			return Response::json('In valid token', 444);
		}
		$user->password = Hash::make(Input::get('newPassword', ''));
		$user->save();
		return Response::json('Password successfully changed', 200);
	}

	public function getLogout ()
	{
		if (! Auth::user())
		return Response::json('Invalid token', 444);

		$expToken = new ExpiredToken(['token' => Request::header('auth') ? Request::header('auth') : '']);
		$expToken->save();
		Auth::logout();
		return Response::json('', 200);
	}

	/**
  *
  * Will authenticate the agent with the bank to obtain a freshness factor
  */
  private function loginRBL ($vendor)
  {
    date_default_timezone_set('Asia/Kolkata');
		$string = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$string_shuffled = str_shuffle($string);
		$requestId = substr($string_shuffled, 1, 6);

  	//Call API for getting the freshness factor
    $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];
  	$data = [
      'terminalId' => $vendor->terminal_id,
      'freshnessFactor' => '00',
      'transType' => '106',
      'csrId' => $vendor->csr_id,
      'requestId' => $requestId,
      'resentCount' => '1',
      'deviceId' => $vendor->device_id,
      'txnTime' => date("M j, Y G:i:s A"),
      'object' => [
        'csrPassword' => $vendor->csr_password,
        'csrId' => $vendor->csr_id
      ],

      'version'=>'1.2.8.1'
    ];
    $body = Unirest\Request\Body::json($data);
		// @TODO: Use events to log.
    $bank_login_log = BankLoginLog::create(['user_id' => $vendor->user_id, 'request' => json_encode($body)]);
    Log::info("Login request: ".json_encode($body));

    $response = Unirest\Request::post(getenv('RBL_URL'), $headers, $body);
    $bank_login_log->response = json_encode($response->body);
    $bank_login_log->save();

    if ($response->code >= 400) {
      Log::info($response->code.' '.json_encode($response->body));
      return false;
    }

    return $response->body;
  }
}
