<?php

use Acme\Auth\Auth;
use \Carbon\Carbon;

class SessionsController extends BaseController {

  function __construct()
  {

    $this->beforeFilter('guest',
      ['only' => ['getLogin', 'getRegister', 'getPasswordResetPage', 'getVerifyEmail']]);

    $this->beforeFilter('guest.json',
      ['only' => ['postLogin', 'postRegister', 'postSetPasswordResetToken', 'postResetPassword', 'postVerifyPhone', 'postResendEmailToken', 'postResendSMSToken']]);
  }

  /**
  * GET /login
  */
	public function getLogin()
	{
    return View::make('sessions.login');
	}

  /**
  * POST /login
  * Err codes:
  * 3 - mobile not verified, 2 - email not verified, 4 - status is inactive, 5 - bank authentication failed
  */
  public function postLogin()
	{
    $user = Auth::validate(Input::all());
    if (! $user)
    {
      return Response::json(['message' => 'Invalid credentials'], 500);
    }
    // if ($user->email_verified == 0) {
    //   return Response::json(['message' => 'Email is not yet verified.', 'code' => 2], 403);
    // }
    if ($user->phone_verified == 0) {
      return Response::json(
          ['message' => 'Mobile no is not yet verified.', 'code' => 3, 'email' => $user->email], 403);
    }
    if ($user->status == 0)
    {
      return Response::json(
          ['message' => 'Account is deactive.', 'code' => 4, 'email' => $user->email], 403);
    }

    $vendor = Vendor::where('user_id', $user->id)->first();
    if(\Cookie::get('user_type') == 1) {
      $rblResponse = $this->loginRBL($vendor);
      if (! $rblResponse || $rblResponse->responseCode == '01')
      {
        \Log::info(json_decode($rblResponse));
        return Response::json(
        ['message' => 'Could not authenticate with the bank.', 'code' => 5], 403);
      }
      \Log::info(json_encode($rblResponse));
      /** Update the vendor's freshness_factor with the new freshness_factor
      *   received from the bank on successful authentication
      */
      $vendor->freshness_factor = $rblResponse->nextFreshnessFactor;
      $vendor->save();
    }



    Auth::login($user);
    Event::fire('user.login', ['user' => Auth::user(), 'vendor' => $vendor]);
    return ['message' => 'Logged in successfully.'];
	}

  public function postResetBankSession ()
  {
    
    if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
    $user = Auth::user();
    //if ($user->type != 4 || ! in_array('dmt', $user->permissions)) 
      //return Response::json(['message' => 'Unauthorized access'], 401);
    if (! Input::has('user_id')) return Response::json(['message' => 'Missing important information.'], 422);
    //$output = $this->loginRBL($user);

    //Call API for getting the freshness factor
    $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];

     $data = [
      'mobileNo' => (string)\Cookie::get('mobileno')
       ];
      
    $body = Unirest\Request\Body::json($data);

    $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/restartsession', $headers, $body);
    $response_data=json_decode($response->raw_body);
    //dd($response_data);
    if($response_data->status == 1)
    {
      //\Cookie::forget('session_timeout');
      Cookie::queue('session_timeout',  $response_data->session_timeout, 60);
      Cookie::queue('user',  $response_data->sessiontoken, 60);
      //return $response_data;

    }
    else{
       return Response::json(['message' => 'Authentication with bank failed.'], 401);
    }

    return Response::json('', 200);
  }


  /**
  *
  * Will authenticate the agent with the bank to obtain a session token and timeout
  */
  private function loginRBL ($user)
  {
    $headers = [
      'Accept' => 'text/xml',
      'Content-Type' => 'text/xml'
    ];
      $data = '<channelpartnerloginreq>
      <username>HariPrasath</username>
       <password>FD91D4C2CDEBD9C6AA547978672D06826441EF46</password>
        <bcagent>'.(string)\Cookie::get('bcagent').'</bcagent>
      </channelpartnerloginreq>';

      // $data = '<channelpartnerloginreq>
      //   <username>DIGITALINIDIA</username>
      //   <password>a4650ff2a34d6c552de2ea8de4d8075f86996257</password>
      //   <bcagent>'.$user->dmt_vendor->bc_agent.'</bcagent>
      // </channelpartnerloginreq>';
      
    $input = Parser::xml($data);
    $bank_login_log = BankLoginLog::create(['user_id' => $user->id, 'request' => json_encode($input)]);
    Log::info("Login request: ".json_encode($input));

    Unirest\Request::verifyPeer(false);
    $response = Unirest\Request::post(getenv('RBL_DMT_URL'), $headers, $data);
    $output = Parser::xml(mb_convert_encoding($response->body, 'UTF-16', 'UTF-8'));
    
    $bank_login_log->response = json_encode($output);
    $bank_login_log->save();

    if ($response->code >= 400) {
      Log::info($response->code.' '.json_encode($response->body));
      return false;
    }

    if ($output['status'] == 0) return false;
    
    return $output;
  }

  /**
  * POST /login-global
  * will auhtenticate users from this software for global single signin
  */
  public function postLoginGlobal ()
  {
    $user = Auth::validate(Input::all());
    if (! $user) {
      return Response::json(['message' => 'Invalid credentials'], 500);
    }
    if ($user->status == 0) {
      return Response::json(
          ['message' => 'Account is inactive.', 'code' => 4, 'email' => $user->email], 403);
    }
    Event::fire('user.login-global', [Auth::user()]);
    return $user;
  }

  /**
  * GET /register
  */
  public function getRegister ()
  {
    return View::make('sessions.register');
  }

  /**
  * POST /register
  */
  public function postRegister ()
  {
    $rules = [
      'name' => 'required',
      'email' => 'required|email|unique:users',
      'phone_no' => 'required',
      'password' => 'required|min:5',
      'password_conf' => 'required|same:password'
    ];
    $validator = Validator::make(Input::all(), $rules);
    if ($validator->fails()) {
      return Response::json(['message' => 'Validation failed.', 'errors' => $validator->messages()], 500);
    }
    $user = new User(Input::only('name', 'email', 'phone_no', 'password'));
    $user->password = Hash::make(Input::get('password'));
    $user->status = 1;
    $user->save();
    Event::fire('user.registered', [$user]);
    return $user;
  }

  /**
  * GET /logout
  */
  public function getLogout ()
  {
  $mobile=Cookie::get('mobileno');
    
    if(isset($mobile)){
      $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];

     $data = [
      'mobilenumber' => (string)\Cookie::get('mobileno')
       ];
      
    $body = Unirest\Request\Body::json($data);

  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/logout', $headers, $body);
  
  $response_data=json_decode($response->raw_body);
// $errorrem= new ErrorRmitter();
//       $errorrem->request=json_encode($data);
//       $errorrem->response=json_encode($response);
//       $errorrem->save();

    }
    


    $user = Auth::user();
    if (! $user)
      return Redirect::to('/login');
    Auth::logout();
    Event::fire('user.logout', [$user]);

    //  $headers = [
    // 'Accept' => 'application/json',
    // 'Content-Type' => 'application/json',
    // 'auth' => \Cookie::get('tracker')
    // ];
   



    // $response = \Unirest\Request::get(getenv('AUTH_URL').'/logout', $headers); 
     
    \Cookie::forget('user');
    \Cookie::forget('userid');
    \Cookie::forget('bcagent');
    \Cookie::forget('user_type');
    \Cookie::forget('parentid');
    \Cookie::forget('mobileno');
    \Cookie::forget('user_name');
    \Cookie::forget('tracker');
    \Cookie::forget('session_timeout');

// if(\Cookie::get('portalid') == 'v3' )
// {
//   return Redirect::to('http://aeps.digitalindiapayments.com/login');
    
// }else
// {
return Redirect::to(getenv('AUTH_URL').'/login');
//}
  
  }

  /**
  * POST /password/actions/reset-token/
  */
  public function postSetPasswordResetToken ()
  {
    if (! Input::has('email')) {
      return Response::json(['message' => 'Invalid request.'], 400);
    }
    $user = User::where('email', Input::get('email'))->first();
    if (! $user) {
      return Response::json(['message' => 'User does not exist.'], 500);
    }
    $token = md5(time().'_'.$user->id);
    $pwdToken = PasswordResetToken::create([
      'user_id' => $user->id,
      'token' => $token,
      'ip' => Request::getClientIp()
    ]);
    Event::fire('user.password.reset-request', [$pwdToken]);
    return $pwdToken;
  }

  /**
  * GET /password/actions/reset-token/{token}
  */
  public function getPasswordResetPage ($token)
  {
    $tokenObj = PasswordResetToken::getToken($token);
    if (! $tokenObj) return 'Invalid or expired token';
    return View::make('forgot-password.forgot-password');
  }

  /**
  * POST /password/actions/reset-token/{token}
  */
  public function postResetPassword ($token)
  {
    $rules = [
      'password' => 'required|min:5',
      'password_conf' => 'required|same:password'
    ];
    $validator = Validator::make(Input::all(), $rules);
    if ($validator->fails()) {
      return Response::json(
        ['message' => 'Validation failed.', 'errors' => $validator->messages()], 400
        );
    }
    $tokenObj = PasswordResetToken::getToken($token);
    if (! $tokenObj) return Response::json(['message' => 'Invalid or expired token'], 403) ;

    $user = $tokenObj->user;

    $user->password = Hash::make(Input::get('password'));
    $user->save();
    $tokenObj->status = 1;
    $tokenObj->save();
    Event::fire('password.changed', [$user]);
    return $user;
  }

  /**
  * GET /verification/email/{token}
  *
  * Helps verify email of user on registration
  */
  public function getVerifyEmail ($token)
  {
    $user = User::where('email_token', $token)->first();
    if (! $user) {
      return 'Invalid token';
    }
    $user->email_verified = 1;
    $user->email_token = null;
    $user->save();
    \Event::fire('user.email-verified', [$user]);
    return \Redirect::to('/landing')->withMessage('Email is successfully verified. Please login to continue.');
  }

  /**
  * POST /verification/phone/{otp}
  *
  * Helps verify phone_no of user on registration
  */
  public function postVerifyPhone ($otp)
  {
    if (! Input::has('email')) {
      return Response::json(['message' => 'Email is not sent.'], 400);
    }
    $user = User::where('sms_token', $otp)->where('email', Input::get('email'))->first();
    if (! $user) {
      return Response::json(['message' => 'Invalid OTP.'], 500);
    }
    $user->phone_verified = 1;
    $user->sms_token = null;
    $user->save();
    \Event::fire('user.phone-verified', [$user]);
    return ['message' => 'Phone number verified successfully.'];
  }

  /**
  * POST /verification/email/actions/resend
  *
  * Helps resend email verification token
  */
  public function postResendEmailToken ()
  {
    if (! Input::has('email')) {
      return Response::json(['message' => 'Please send an email'], 400);
    }
    $user = User::where('email', Input::get('email'))->first();
    if (! $user) {
      return Response::json(['message' => 'No user found'], 500);
    }
    $token = md5(time().'_'.$user->id);
    $user->email_token = $token;
    $user->save();
    return [];
  }

  /**
  * POST /verification/phone/actions/resend
  *
  * Helps resend phone_no verification token
  */
  public function postResendSMSToken ()
  {
    if (! Input::has('email')) {
      return Response::json(['message' => 'Please send an email'], 400);
    }
    $user = User::where('email', Input::get('email'))->first();
    if (! $user) {
      return Response::json(['message' => 'No user found'], 500);
    }
    $token = md5(time().'_'.$user->id);
    $user->sms_token = $token;
    $user->save();
    return [];
  }

}
