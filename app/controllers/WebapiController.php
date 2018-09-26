<?php
use Acme\Auth\Auth;
use Acme\SMS\SMS;

/**
*  A controller that deals with action APIs
*/
class WebapiController extends BaseController
{
	

	public function postlogin ()
 	{

      //Call API for getting the freshness factor
    $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];

		 $data = [
      'phone_no' => '9029197048',
      'password' => 'dipl@123',
      'captcha' => '123'
       ];

     
    $body = Unirest\Request\Body::json($data);

  $response = Unirest\Request::post(getenv('DIPL_API'), $headers, $body);

  dd($response);
	}


  public function remitter()
  {
$headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];

     $data = [
      'remittermobilenumber' => '9029197048',
      'remittername' => 'dipl@123',
      'remitteraddress' => '123',
      'pincode'=>'40072',
      'cityname'=>'mumbai',
      'statename'=>'maha',
      'lremitteraddress'=>'fsfs',
      'lpincode'=>'40125',
      'lstatename'=>'gfgfh',
      'sessiontoken'=>\Cookie::get('user'),
      'lcityname'=>'mumnbai'
       ];

     
    $body = Unirest\Request\Body::json($data);

  $response = Unirest\Request::post('http://43.224.136.144:8080/DMTService/Remitteraddrequest', $headers, $body);

  dd($response);
  }

  public function benf()
  {
$headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];

     $data = [
      'remitterid' => '123',
      'beneficiaryname' => 'dipl@123',
      'beneficiarymobilenumber' => '123',
      'beneficiaryemailid'=>'40072',
      'relationshipid'=>'0',
      'ifscode'=>'maha',
      'accountnumber'=>'013456789',
      'flag'=>'40125',
      
       ];

         
     
    $body = Unirest\Request\Body::json($data);

  $response = Unirest\Request::post('http://192.168.1.104:8080/DMTService/Beneficiaryaddrequest', $headers, $body);

  dd($response);
  }



  public function searchRemitter()
  {
  $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];

     $data = [
      'mobilenumber' => '9004835502'
       ];

         
     
    $body = Unirest\Request\Body::json($data);

  $response = Unirest\Request::post('http://192.168.1.104:8080/DMTService/SearchRemitter', $headers, $body);

  //dd($response);
  }




  public function deleteBeneficiary()
  {
  $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];

     $data = [
      'remitterid' => '123',
      'beneficiaryid'=>'1'
       ];

         
     
    $body = Unirest\Request\Body::json($data);

  $response = Unirest\Request::post('http://192.168.1.104:8080/DMTService/DeleteBeneficiary', $headers, $body);

  dd($response);
  }




  public function dashboard()
  {
  $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];

     $data = [
      'userid' => '55',
      'parent_userid'=>'6',
      'tran_status'=>'approved',
      'user_type'=>'1'
       ];

         
     
    $body = Unirest\Request\Body::json($data);

  $response = Unirest\Request::post('http://192.168.1.106:8080/DMTService/Dashboard', $headers, $body);

  dd($response);
  }




public function walletRequest()
  {
  $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];

     $data = [
      'user_id' => '63',
      'parent_id'=>'62',
      'amount'=>'500.00',
      'remarks'=>'Test Request',
      'process_type'=>'1', //1-DMT, 2- AEPS
      'request_type'=>'2' // 1 - Request to admin , 2 - Request to dist
       ];

     
    $body = Unirest\Request\Body::json($data);

  $response = Unirest\Request::post('http://192.168.1.112:8080/DMTService/TopupRequest', $headers, $body);

  dd($response);
  }




public function walletRequestAdmin()
  {
  $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];


     $data = [
      'amount' => '500',
      'transfer_mode'=>'1',    //IMPS-1/NEFT-2, CD-4,RTGS3,CDM-5
      'bank'=>'AXIS',
      'branch'=>'Mumbai',
      'referenceno'=>'123456',
      'user_id'=>'63',
      'parent_id'=>'61',
      'process_type'=>'1',
      'request_type'=>'1',
      'remarks'=>'testing Admin purpose',

       ];
         
     
    $body = Unirest\Request\Body::json($data);

  $response = Unirest\Request::post('http://192.168.1.112:8080/DMTService/TopupRequest', $headers, $body);

  dd($response);
  }





public function requestApprovalDist()
  {
  $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];


     $data = [
      'request_id' => '14'
       ];
         
     
    $body = Unirest\Request\Body::json($data);

  $response = Unirest\Request::post('http://192.168.1.108:8080/DMTService/TopupRequestApproval', $headers, $body);

  dd($response);
  }





public function profile()
  {
  $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];


     $data = [
      'user_id'=>'62'
       ];
         
     
    $body = Unirest\Request\Body::json($data);

  $response = Unirest\Request::post('http://192.168.1.106:8080/DMTService/ProfileRequest', $headers, $body);

  dd($response);
  }




public function ChangePassword()
  {
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

  dd($response);
  }




public function forgotPasswordOTP()
  {
  $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];


     $data = [
      'mobilenumber'=>'9004835502'
       ];
         
     
    $body = Unirest\Request\Body::json($data);

  $response = Unirest\Request::post('http://192.168.1.106:8080/DMTService/forgotPasswordOTP', $headers, $body);

  dd($response);
  }




  public function forgotPasswordConfirm()
  {
  $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];


     $data = [
      'otp'=>'12345',
      'password'=>'rss123',
      'confirmPassword'=>'rss123'
       ];
         
     
    $body = Unirest\Request\Body::json($data);

  $response = Unirest\Request::post('http://192.168.1.106:8080/DMTService/forgotPasswordConfirm', $headers, $body);

  dd($response);
  }


}	
