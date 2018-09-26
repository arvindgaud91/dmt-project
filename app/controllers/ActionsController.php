<?php
use Acme\Auth\Auth;
use Acme\SMS\SMS;

/**
*  A controller that deals with action APIs
*/
class ActionsController extends BaseController
{
	function __construct()
	{
		
	}

	public function postPasswordResetOTP ()
	{
		/*Webservices*/
		$headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json'
	    ];


	     $data = [
	      'oldPassword'=>'789654',
	      'newPassword'=>'',
	      'confirmPassword'=>'',
	      'user_id'=>'61'
	       ];
	         
	     
	    $body = Unirest\Request\Body::json($data);

	  $response = Unirest\Request::post('http://192.168.1.106:8080/DMTService/ChangePassword', $headers, $body);
		/*End webservices*/

		if (! Input::has('phone_no')) {
			return Response::json(['message' => 'Missing info'], 422);
		}
		$user = User::where('phone_no', Input::get('phone_no'))
			->where('type', 4)
			->first();
		if (! $user) return Response::json(['code' => 1], 422);
		$otp = mt_rand(1000, 9999);
		$otpObj = new PasswordResetOTP(['user_id' => $user->id, 'otp' => $otp.'', 'ip' => Request::getClientIp()]);
		$otpObj->save();
		// @todo send SMS
		// SMS the OTP
		SMS::send($user->phone_no, 'Yor OTP is '.$otp.'. Digital India Payments.');
		return [];
	}

	public function postNewPasswordOTP ()
	{
		if (! Input::has('phone_no') || ! Input::has('otp') || ! Input::has('password') || ! Input::has('password_confirmation')) {
			return Response::json(['message' => 'Missing info'], 422);
		}
		$user = User::where('phone_no', Input::get('phone_no'))->first();
		if (! $user) return Response::json(['code' => 404], 422);
		$status = PasswordResetOTP::getOTP(Input::get('otp'), $user->id);
		if (! $status) return Response::json(['code' => 1], 422);
		$p = Hash::make(Input::get('password'));
		// $user->password = $p;
		// return Response::json($user->password, 500);
		// $user->save();
		User::where('phone_no', Input::get('phone_no'))->update(['password' => $p]);
		Auth::login($user);
		return $user;
	}
}