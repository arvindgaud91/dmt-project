<?php
use Acme\Auth\Auth;
use Acme\SMS\SMS;

/**
*  A controller that deals with remitter APIs
*/
class RemitterController extends BaseController
{
	function __construct()
	{
		
	}

	public function getAddRemitter ($id)
	{ 
		
		$data['phone_no'] =$id;
		return View::make('sender.add')->with($data);
	}

public function getAddRemitterid()
	{ 
		
		//$data['phone_no'] =$id;
		return View::make('sender.adds');
	}
	

	public function getBankBranchByIfsc ()
	{
		if (! Input::has('ifsc')) return Response::json(['message' => 'Missing IFSC Code', 'code' => 1], 422);

		$ifsc = Input::get('ifsc');
		
/*Web services*/
		$headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json'
	    ];

	    $data = [
	       'ifsc_code'=>$ifsc
	       		       
	   	];

		$body = Unirest\Request\Body::json($data);

		$response = Unirest\Request::post(getenv('WS_URL').'/DMTService/bankifsccoderequest', $headers, $body);


		if($response->body->status == 1)

		{   
			// $errorrem= new ErrorRmitter();
			// $errorrem->request=json_encode($data);
			// $errorrem->response=json_encode($response);
			// $errorrem->save();
			 


          if (! $response->body) return Response::json(['message' => 'IFSC code not found.', 'code' => 2], 422);
		return Response::json($response->body, 200);
		

	    }else
	    {return Response::json('', 400);
		

	    }

		
	}

	public function getAddressInfoByPincode ()
	{
		if (! Input::has('pincode')) return Response::json(['message' => 'Missing Pincode', 'code' => 1]);
		$pincode = Input::get('pincode');
		$pincodeObj = Pincode::where('pincode', $pincode)->first();
		if (! $pincode) return Response::json(['message' => 'Pincode not found.', 'code' => 2]);
		return Response::json($pincodeObj, 200);
	}


//function add remitter 

	public function postAddRemitter ()
	{
         
		if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
		$user = Auth::user();
		

		
		$mobile_number=Input::get('phone_no');
		$name=Input::get('name');
		$address=Input::get('address');
		$pincode=Input::get('pincode');
		$state=Input::get('state');
		$city=Input::get('city');
        
        /*Web service code*/
        $headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json'
	    ];

	     $data = [
	      'remittermobilenumber' => $mobile_number,
	      'sessiontoken'=>\Cookie::get('user'),
	      'bcagent'=>(string)\Cookie::get('bcagent'),
	      'remittername' => $name,
	      'remitteraddress' =>$address,
	      'remitteraddress1' =>$address,
	      'remitteraddress2'=>$address,
	      'pincode'=>$pincode,
	      'cityname'=>$city,
	      'statename'=>$state,
	      'alternatenumber'=>"9090000000",
	      'idproof'=>"idproof",
	      'idproofnumber'=>'idproofnumber',
	      'idproofissuedate'=>'',
	      'idproofexpirydate'=>'',
	      'idproofissueplace'=>'Mumbai',
	      'lremitteraddress'=>'Mumbai',
	      'lpincode'=>$pincode,
	      'lstatename'=>$state,
	      'lcityname'=>$city,
	      'user_id'=> '2'
	       ];

	     
	    $body = Unirest\Request\Body::json($data);

	  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/Remitteraddrequest', $headers, $body);


	    // $errorrem= new ErrorRmitter();
	    // $errorrem->request=json_encode($data);
	    // $errorrem->response=json_encode($response);
	    // $errorrem->save();

	 // dd($response);


	  if($response->code == 200)
		{  
               // dd($response->body->status);
          
		  if($response->body->status ==1)
		      {
		      
               return Response::json(['remitter_id' => $response->body->remitterid,'message' =>'Sender Added successfully !!','status'=>1], 200);


		      } else
		      {
    return Response::json(['remitter_id' => $response->body->description,'message' =>$response->body->description,'status'=>0], 422); 
                    
		      }   
			      
	    
		

	    }else
	   {
      //return redirect('/remitter/add');
	
	  }

        /*End web services code*/

	}


 // function get remitters list

	public function getRemitters ()
	{
		
		$user = Auth::user();
		

		/*Web services*/
		$headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json'
	    ];

	    $data = [
	       'user_id'=>(string)\Cookie::get('mobileno'),
	       'sessiontoken'=>\Cookie::get('user'),
	       'bc_agent_id'=>(string)\Cookie::get('bcagent')		       
	   	];

		$body = Unirest\Request\Body::json($data);

		$response = Unirest\Request::post(getenv('WS_URL').'/DMTService/remitterviewrequest', $headers, $body);
//dd($response);

		if($response->code == 200)

		{   
			// $errorrem= new ErrorRmitter();
			// $errorrem->request=json_encode($data);
			// $errorrem->response=json_encode($response);
			// $errorrem->save();

			  if(isset($response->body->response))
              {

              	
              	 $response_data=json_decode($response->raw_body);
    
		          foreach ($response_data->response as $requests) {

		            $request_datas[] = json_decode($requests);
                       }
     

            

              }else
              {
              	$request_datas='';
              }

              return View::make('sender.list')->with('data',$request_datas);

		

	    }
	}

// function get remitter list details

	public function getRemitterById ()
	{


		$data =Input::all();
		//dd($remitter_id['data']['remitter_id']);
		if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
		$user = Auth::user();
		if ($user->type != 4 || ! in_array('dmt', $user->permissions)) 
		return Response::json(['message' => 'Unauthorized access'], 401);

		
	 return View::make('sender.receiver-details')->with($data);
		           

		
	        
	}

   
 //function search remitter
   
	public function postSearchRemitter ()
	{
		ini_set('precision',1);
		if(!Input::get('phone_no')) return Redirect::to('remitters');
		/*Web services*/
			$headers = [
		      'Accept' => 'application/json',
		      'Content-Type' => 'application/json'
		    ];

		     $data = [
		      'mobilenumber' => Input::get('phone_no'),
		       "flag"=>"1",
		       'user_id'=>'2',
		       'sessiontoken'=>\Cookie::get('user'),
		       'bcagent'=>(string)\Cookie::get('bcagent')		       
		   ];

		    $body = Unirest\Request\Body::json($data);

		  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/SearchRemitter', $headers, $body);


			//dd($response->body->response->remitterdetail);
		/*End web services*/
		if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
		$user = Auth::user();
		
		if (! Input::has('phone_no')) return Response::json(['message' => 'Missing important information']);
		//$remitter = Remitter::where('phone_no', Input::get('phone_no'))->first();
			$errorrem= new ErrorRmitter();
			    $errorrem->request=json_encode($data);
			    $errorrem->response=json_encode($response);
			    $errorrem->save();
		if($response->code == 200)

		{        
			      if(isset($response->body->response))
			                  {
			                  	if($response->body->response->status == 1)
                        		{
  	                        	$respo['data']=json_decode($response->raw_body, false, 512, JSON_BIGINT_AS_STRING)->response;


                        		}else
                                 {
                                 	return Redirect::to('/remitter/add/'.Input::get('phone_no'));
                                 }
			                  }else
			                  {
			                  	$respo['data']='';
			                  	return Redirect::to('/remitter/add/'.Input::get('phone_no'));
			                  }
			                 $respo['phone_no']=Input::get('phone_no');
			                  //dd(json_decode($response->raw_body, false, 512, JSON_BIGINT_AS_STRING)->response);
			 return View::make('sender.receiver-details')->with($respo);

		

	    }else
	   {
      return Redirect::to('/remitter/add/'.Input::get('phone_no'));
	
	  }
	}





	 //get function search remitter
   
	public function getmobileSearchRemitter ($phone_no)
	{
		ini_set('precision',1);
		//if(!Input::get('phone_no')) return Redirect::to('remitters');
		/*Web services*/
			$headers = [
		      'Accept' => 'application/json',
		      'Content-Type' => 'application/json'
		    ];

		     $data = [
		      'mobilenumber' => $phone_no,
		       "flag"=>"1",
		       'user_id'=>'2',
		       'sessiontoken'=>\Cookie::get('user'),
		       'bcagent'=>(string)\Cookie::get('bcagent')		       
		   ];

		    $body = Unirest\Request\Body::json($data);

		  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/SearchRemitter', $headers, $body);


			//dd($response->body->response->remitterdetail);
		/*End web services*/
		if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
		$user = Auth::user();
		
		if (! $phone_no) return Response::json(['message' => 'Missing important information']);
		//$remitter = Remitter::where('phone_no', Input::get('phone_no'))->first();
			// $errorrem= new ErrorRmitter();
			//     $errorrem->request=json_encode($data);
			//     $errorrem->response=json_encode($response);
			//     $errorrem->save();
		if($response->code == 200)
		{        
			      if(isset($response->body->response))
			                  {
			                  	if($response->body->response->status == 1)
                        		{
  	                        	$respo['data']=json_decode($response->raw_body, false, 512, JSON_BIGINT_AS_STRING)->response;


                        		}else
                                 {
                                 	return Redirect::to('/remitter/add/'.$phone_no);
                                 }
			                  }else
			                  {
			                  	$respo['data']='';
			                  	//return Response::json(['respo'=>$respo] , 400);	
			                  }
			                 //$respo['phone_no']=$phone_no;
			                  //dd(json_decode($response->raw_body, false, 512, JSON_BIGINT_AS_STRING)->response);
			// return View::make('sender.receiver-details')->with($respo);

	             return $respo['data'];	
		

	    }else
	   {$respo['data']='';
     return Response::json($respo , 400);	
	
	  }
	}

//get function serach remitter
// public function postSearchRemitter ($phone_no)
// 	{
// 		/*Web services*/
// 			$headers = [
// 		      'Accept' => 'application/json',
// 		      'Content-Type' => 'application/json'
// 		    ];

// 		     $data = [
// 		      'mobilenumber' => $phone_no,
// 		       "flag"=>"1",
// 		       'user_id'=>'2',
// 		       'sessiontoken'=>\Cookie::get('user'),
// 		       'bcagent'=>(string)\Cookie::get('bcagent')		       
// 		   ];

// 		    $body = Unirest\Request\Body::json($data);

// 		  $response = Unirest\Request::post('http://43.224.136.144:8080/DMTService/SearchRemitter', $headers, $body);


// 			//dd($response->body->response->remitterdetail);
// 		/*End web services*/
// 		if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
// 		$user = Auth::user();
		
// 		if (! Input::has('phone_no')) return Response::json(['message' => 'Missing important information']);
// 		//$remitter = Remitter::where('phone_no', Input::get('phone_no'))->first();
// 		if($response->code == 200)

// 		{        $errorrem= new ErrorRmitter();
// 			    $errorrem->request=json_encode($data);
// 			    $errorrem->response=json_encode($response);
// 			    $errorrem->save();
// 			      if(isset($response->body->response))
// 			                  {
// 			                  	$respo=$response->body->response;
// 			                  }else
// 			                  {
// 			                  	$respo='';
// 			                  }
// 			 return View::make('sender.receiver-details')->with('data',$respo);

		

// 	    }else
// 	   {
//       return redirect('/remitter/add');
	
// 	  }
// 	} 


//function get Add Beneficiary



	public function getAddBeneficiary ($remitter_id)
	{	

		//dd($remitter_id);
		//$data['remitter_id'] = $remitter_id;


			$responsedata=explode('-', $remitter_id);

if(isset($responsedata))
{
$data['remitter_id'] = $responsedata[0];
$data['phone_no'] = $responsedata[1];
}
		$headers = [
		      'Accept' => 'application/json',
		      'Content-Type' => 'application/json'
		    ];
		// $data['banks'] = DmtBank::orderBy('preference','DESC')->orderBy('name','asc')->get();
		//$body = Unirest\Request\Body::json($data);
		$response = Unirest\Request::post(getenv('WS_URL').'/DMTService/BankRequest', $headers);
		//dd($response->body->BankList);
		if($response->code == 200)

		{        
			if(isset($response->body->BankList))
          	{
          		$data['banks'] =$response->body->BankList;
          	}else
          	{
          		$data['banks'] ='';
          	}
			 return View::make('sender.addReceiver')->with($data);
	    }
		
	}

//function get validation beneficiary

     public function getvalidationBeneficiary ($id)
	{	
		

		$responsedata=explode('-', $id);

if(isset($responsedata))
{
$data['ben_id'] = $responsedata[0];
$data['ben_name'] =$responsedata[1];
$data['ben_accountno'] =$responsedata[2];
$data['ben_ifsc'] =$responsedata[3];
$data['phone_no'] =$responsedata[4];
}


		//$data['ben_id'] = RemitterBeneficiary::with('DmtBankBranch')->with('DmtBankBranch.DmtBank')->find($id);
		//dd($data);
		return View::make('sender.receiverValidation')->with($data);
	}

//get otp  deleted beneficiary

	public function getotpdeleteBeneficiary ($remitter_id)
	{	
		$data['remitter_id'] = $remitter_id;
		return View::make('sender.receiverOtp')->with($data);
	}
//get otp beneficiary

	public function getotpBeneficiary ($remitter_id,$id)
	{	//dd($id);
		$data['remitter_id'] = $remitter_id;
		$data['phone_no']=$id;
		return View::make('sender.otpBeneficiary')->with($data);
	}
// function get refund page

	public function refundReport()
	{
		return View::make('sender.refund');
	}

// function check validation api 

    public function checkvalidation()
    {
    
   
		if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
		$user = Auth::user();

		    $remitter_id=Input::get('remitter_id');
		    $beneficiaryname=Input::get('name');
			$bname=Input::get('bank_name');
			$ifsc=Input::get('bank_ifsc');
			$reference=(string)\Cookie::get('bcagent').time();
			$account_number=Input::get('account_number');

			/*Web service*/
			$headers = [
			      'Accept' => 'application/json',
			      'Content-Type' => 'application/json'
			    ];

			     $data = [
			      'sessiontoken' =>(string)\Cookie::get('user'),
			      'userid'=>(string)\Cookie::get('userid'),
			      'bcagent' =>(string)\Cookie::get('bcagent'),
			      'remitterid' =>$remitter_id,
			      'beneficiaryname'=>$beneficiaryname,
			      'beneficiarymobilenumber'=>'9999999999',
			      'accountnumber'=>$account_number,
			      'ifscode'=>$ifsc,
			      'channelpartnerrefno'=>$reference,
			      
			       ];

			        //dd($data); 
			     
			    $body = Unirest\Request\Body::json($data);

			  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/Beneficiaryaccountvalidation', $headers, $body);

			   // $errorrem= new ErrorRmitter();
			   //  $errorrem->request=json_encode($data);
			   //  $errorrem->response=json_encode($response);
			   //  $errorrem->save();


			 
			    if($response->body->status == 1)
			    {

	             return Response::json(['message'=>'success','remitterid'=>$remitter_id,'status'=>1] , 200);	
			    }else
			    {
                 return Response::json(['message'=>$response->body->description,'remitterid'=>'','status'=>0] , 400);	
			    }
			  
			
		      

	    

    }
   
//function add Beneficiary

	public function postAddBeneficiary ()
	{
     if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
		$user = Auth::user();
        $id=Input::get('id');
       
		$ifsc=Input::get('bank_ifsc');
		$account_number=Input::get('account_number');

        /*Web service code*/
        $headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json'
	    ];

	     $data = [
	      'remitterid'=>(string)$id,
	      'sessiontoken'=>\Cookie::get('user'),
	      'bcagent'=>(string)\Cookie::get('bcagent'),
	      'beneficiaryname' =>Input::get('name'),
	      'beneficiarymobilenumber' =>'9004596609',
	      'beneficiaryemailid'=>'pradisp@gmail.com',
	      'relationshipid'=>'0',
	      'ifscode'=>$ifsc,
	      'accountnumber'=>$account_number,
	      'flag'=>'2',
	      'mmid'=>''
	       ];

	    
	    $body = Unirest\Request\Body::json($data);

	  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/Beneficiaryaddrequest', $headers, $body);


	    // $errorrem= new ErrorRmitter();
	    // $errorrem->request=json_encode($data);
	    // $errorrem->response=json_encode($response);
	    // $errorrem->save();

	  //dd($response);

        /*End web services code*/
               
	    

	    	if($response->body->status ==1)
	    	{
	    	if(isset($response->body->beneficiaryid))
	    	{
                  $beneficiaryid=$id.'-'.$response->body->beneficiaryid;
	    	}else
	    	{
                  $beneficiaryid='';
	    	}
	    	return Response::json(['id' => $beneficiaryid ,'status' =>1] , 200);
	      }else
	      {

	      return Response::json(['message'=>$response->body->description,'status' =>0] , 422);

	      }


	     
		
	}

//function delete Beneficiary

	public function deleteBeneficiaryweb ()
	{
		if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
		$user = Auth::user();

		/*Web services*/
		 $headers = [
		      'Accept' => 'application/json',
		      'Content-Type' => 'application/json'
		    ];

		     $data = [
		      'remitterid' =>(string)Input::get('remitterid'),
		      'beneficairyid'=>(string)Input::get('ben_id'),
		      'sessiontoken' =>\Cookie::get('user')
		       ];


		         
		     
		    $body = Unirest\Request\Body::json($data);

		  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/DeleteBeneficiary', $headers, $body);
     //     $errorrem= new ErrorRmitter();
	    // $errorrem->request=json_encode($data);
	    // $errorrem->response=json_encode($response);
	    // $errorrem->save();
		 // dd($response);
		/*ENd web services*/
		
		
		
		if(isset($response->body->status) == 1)
	    {
		     
return Response::json(['beneficiary_id' => Input::get('ben_id')], 200);

			
			
	    }else
	    {
	      
   return Response::json(['message' => 'Beneficiary not found', 'code' => 1]);
	     

	    }

		
		
	}


	//function deleteBeneficiaryResendOTP Beneficiary

	public function deleteBeneficiaryResendOTP ($id)
	{



$responsedata=explode('-', $id);

if(isset($responsedata))
{

$remitterid = $responsedata[1];
$beneficiary =$responsedata[0];
}

    
/*Web services*/
		 $headers = [
		      'Accept' => 'application/json',
		      'Content-Type' => 'application/json'
		    ];

		     $data = [
		      'remitterid' =>(string)$remitterid,
		      'beneficiaryid' =>(string)$beneficiary,
		      'sessiontoken' =>\Cookie::get('user')
		       ];


		         
		     
		    $body = Unirest\Request\Body::json($data);

		  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/Beneficiaryresendotp', $headers, $body);
     //     $errorrem= new ErrorRmitter();
	    // $errorrem->request=json_encode($data);
	    // $errorrem->response=json_encode($response);
	    // $errorrem->save();
		  //dd($response);
		/*ENd web services*/


		    
		    if($response->body->status == 1)
		    {
		     	
		         return Redirect::back()->with('success','OTP Sent to Remitter  Mobile No  !!' );

		    }else
		    {
		    	
		   return Redirect::back()->with('error','OTP Not Send !!' );


		    
		      }   


 
		
	}


//beneficiaries deleted function

public function otpremitter($id)
{

	//dd(Input::all());
		if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
		$user = Auth::user();
		
/*Web services*/
		 $headers = [
		      'Accept' => 'application/json',
		      'Content-Type' => 'application/json'
		    ];

		     $data = [
		      'verficationcode' =>(string)Input::get('otp'),
		      'beneficiaryid'=>(string)Input::get('ben_id'),
		      'sessiontoken' =>\Cookie::get('user')
		       ];


		         
		     
		    $body = Unirest\Request\Body::json($data);

		  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/Beneficairydeletevalidation', $headers, $body);
     //     $errorrem= new ErrorRmitter();
	    // $errorrem->request=json_encode($data);
	    // $errorrem->response=json_encode($response);
	    // $errorrem->save();
		 // dd($response);
		/*ENd web services*/
     if($response->body->status == 1)
     {
     	
     
       return Redirect::back()->with('success','Beneficairy  Deleted successfully..!');   	
     }else
     {
     	

     return Redirect::back()->with('error','Beneficairy Not Deleted..!');
     }


}

//function otp beneficiary

public function otpbeneficiary($id,$ben,$ids)
{
if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
	$user = Auth::user();
$responsedata=explode('-', $id);


    
/*Web services*/
		 $headers = [
		      'Accept' => 'application/json',
		      'Content-Type' => 'application/json'
		    ];

		     $data = [
		      'remitterid' =>(string)$ben,
		      'beneficiaryid' =>(string)$id,
		      'verficationcode'=>(string)Input::get('otp'),
		      'sessiontoken' =>\Cookie::get('user')
		       ];

//dd($data);
		         
		     
		    $body = Unirest\Request\Body::json($data);

		  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/Beneficiaryregistrationvalidation', $headers, $body);
     //     $errorrem= new ErrorRmitter();
	    // $errorrem->request=json_encode($data);
	    // $errorrem->response=json_encode($response);
	    // $errorrem->save();
		//  dd($response);
		/*ENd web services*/


		    
		    if($response->body->status == 1)
		    {
		     	//return Redirect::to('/remitters');
		     	return Response::json(['status' => 1], 200);
		         
		    }else
		    {
		    	
		    	
//return Redirect::back()->with('error','OTP Not Match Please check !!' );

	return Response::json(['status' =>0], 200);	    
		      }        
	


}

// function get otp refund

public function getOtpRefund()
{
    if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
	$user = Auth::user();

/*Web services*/
    $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
      ];

      $data = [
              'RBLtransactionid' =>(string)Input::get('bankTaxid'),
		      'sessiontoken' =>\Cookie::get('user')
      ];

    $body = Unirest\Request\Body::json($data);

    $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/refundotprequest', $headers, $body);
     // $errorrem= new ErrorRmitter();
	    // $errorrem->request=json_encode($data);
	    // $errorrem->response=json_encode($response);
	    // $errorrem->save();
//dd($response);
if($response->body->status == 1)
{
return Response::json(['rbltransactionid' => Input::get('bankTaxid')], 200);
}else
{
   return Response::json(['rbltransactionid' => Input::get('bankTaxid'),'message' => 'Resend OTP FAILED'], 400);
}





}

// private function wallet update api

private function walletUpdates ($dmt_vendor_id, $transaction)
{
      $dmt_vendor = DmtVendor::where('user_id','=',$dmt_vendor_id)->first();
      $dmt_vendor->balance += $transaction['amount'];
      $dmt_vendor->save();
      

       $remitter=Remitter::where('id','=',$transaction['remitter_id'])->first();
      //dd($remitter);
      $DmtTransaction=DmtTransaction::where('id',$transaction['transaction_id'])->first();
       $DmtTransaction->status = 2;
       $DmtTransaction->result = 1;
       $DmtTransaction->refund_status = 1;
       $DmtTransaction->save();

      $remitter_phone=$remitter->phone_no;

      $credit_tx = WalletTransaction::create([
          'user_id' => $dmt_vendor->user_id,
          'transaction_type' => 1,
          'activity' =>$transaction['channelpartnerrefno'].'/'.$remitter_phone,
          'narration' => 'Refund Transfer - ' .$transaction['channelpartnerrefno'],
          'amount' =>$transaction['amount'],
          'balance' => $dmt_vendor->balance
        ]);

      DmtWalletAction::create([
          'user_id' => $dmt_vendor->user_id,
          'amount' => $transaction['amount'],
          'credit_id' => $credit_tx->id,
          'status' => 1,
          'transaction_id' => $transaction['transaction_id'],
          'transaction_type' => 1
        ]);
      
      return true;

}



public function getRefundtransaction()
{
    if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
	$user = Auth::user();

	/*Web services*/
    $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
      ];
		
		$otp=Input::get('otp');
		$bankTransID=Input::get('txid');
      $data = [
              
		      'sessiontoken' =>\Cookie::get('user'),
		       'bcagent'=>(string)\Cookie::get('bcagent'),
		      'channelpartnerrefno'=>\Cookie::get('bcagent').time(),
		      'verficationcode'=>$otp,
		      'parent_id' => '879',
		      'userid' => (string)\Cookie::get('userid'),
		      'RBLtransactionid'=>$bankTransID
      ];

    $body = Unirest\Request\Body::json($data);

    $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/transactionrefundrequest', $headers, $body);
     // $errorrem= new ErrorRmitter();
	    // $errorrem->request=json_encode($data);
	    // $errorrem->response=json_encode($response);
	    // $errorrem->save();
//dd($response);
	if($response->body->status == 1)
	{
	return Response::json(['rbltransactionid' => Input::get('txid')], 200);
	}else
	{
	   return Response::json(['rbltransactionid' => Input::get('txid'),'message' => 'Refund FAILED'], 400);
	}




	// if ($user->type != 4 || ! in_array('dmt', $user->permissions)) 
	// return Response::json(['message' => 'Unauthorized access'], 401);

	// $rbltransactionid=Input::get('txid');
	// $otp=Input::get('otp');
 //    $DmtTransaction=DmtTransaction::where('bank_transaction_id','=',$rbltransactionid)->first();
 //    $channelpartnerrefno=$DmtTransaction['reference_number'];
	// $dmt_vendor_id=$DmtTransaction['user_id'];
	// $remitter_id =$DmtTransaction['remitter_id'];

 //     $headers = ['Accept' => 'text/xml',
 //                 'Content-Type' => 'text/xml' ];

	// $otp_xml_data ="<refundreq>
	// 			    <header>
	// 			    <sessiontoken>".$user->dmt_vendor->session_token."</sessiontoken>
	// 			    </header>
	// 			    <bcagent>".$user->dmt_vendor->bc_agent."</bcagent>
	// 			    <channelpartnerrefno>".$channelpartnerrefno."</channelpartnerrefno>
	// 			    <verficationcode>".$otp."</verficationcode >
	// 			    <flag>1</flag>
	// 			    </refundreq>";

	// Unirest\Request::verifyPeer(false);
 //    $response = Unirest\Request::post(getenv('RBL_DMT_URL'), $headers, $otp_xml_data);
 //    $output = Parser::xml(mb_convert_encoding($response->body, 'UTF-16', 'UTF-8'));
    

 //    if($output['status']==1)
 //    {
    	
	//     $transaction=array('amount'=>$output['amount'],'transaction_id'=>$DmtTransaction['id'],'transaction_type'=>1,'remitter_id'=>$remitter_id,'channelpartnerrefno'=>$channelpartnerrefno);
	//     $walletUpdates=$this->walletUpdates($dmt_vendor_id,$transaction);

 //    return Response::json(['rbltransactionid' => $rbltransactionid], 200);
 //    }else
 //    {
 //     	$description=$output['description'];
 //     	$ErrorRefund= new ErrorRefund();
 //     	$ErrorRefund->request=json_encode($otp_xml_data);
 //     	$ErrorRefund->response=json_encode($output);
 //     	$ErrorRefund->save(); 
 //    return Response::json(['rbltransactionid' => $rbltransactionid,'message' => $description], 422);
 //    }


}


//resend otp ben

public function resendotp($ben_id)
{

    if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
	$user = Auth::user();


/*Web services*/
		 $headers = [
		      'Accept' => 'application/json',
		      'Content-Type' => 'application/json'
		    ];

		     $data = [
		      'remitterid' =>(string)Input::get('otp'),
		      'beneficiaryid' =>(string)Input::get('otp'),
		      'sessiontoken' =>\Cookie::get('user')
		       ];


		         
		     
		    $body = Unirest\Request\Body::json($data);

		  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/Beneficiaryresendotp', $headers, $body);
    //      $errorrem= new ErrorRmitter();
	   //  $errorrem->request=json_encode($data);
	   //  $errorrem->response=json_encode($response);
	   //  $errorrem->save();
		  // dd($response);
		/*ENd web services*/





	 
	    
		if($output['status']==1)
		{
		 return Redirect::to('/remitter/'.$remitter_beneficiaries['id'].'/beneficiary/beneficiary_otp');
		}else
		{
		return Response::json(['rblstatus' => $output['status']], 400);	
		}

	


}



public function requery($trn_id)
{

 if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);

	$user = Auth::user();

	/*Web services*/
		 $headers = [
		      'Accept' => 'application/json',
		      'Content-Type' => 'application/json'
		    ];

		     $data = [
		      'bcagent' =>(string)\Cookie::get('bcagent'),
		      'channelpartnerrefno' =>(string)$trn_id,
		      'sessiontoken' =>\Cookie::get('user')
		       ];


		         
		     
		    $body = Unirest\Request\Body::json($data);

		  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/transactionrequeryrequest', $headers, $body);
     //     $errorrem= new ErrorRmitter();
	    // $errorrem->request=json_encode($data);
	    // $errorrem->response=json_encode($response);
	    // $errorrem->save();

	   // dd($response->body->response->transactionid);
	    
		 if(isset($response_data->transactionid))
		 {
		 if($response_data->status ==1)
		 {$response_data=json_decode($response->body->response);

		 	$transactionid=$response_data->transactionid;
		 	$bankremarks=$response_data->BankRemarks;
		 	 return Redirect::back()->with('success','Transaction Id :-'.$transactionid .'   And Remarks :-'.$bankremarks );
		 }else
		 {
        return Redirect::back()->with('success','Transaction Requery success' );
     	}

		}else
		{
			 return Redirect::back()->with('success','Transaction Requery success' );
		}





}

//Added by ashish for ifsc from bank
	public function getIfscByBank ()
	{
		if (! Input::has('bankId')) return Response::json(['message' => 'Missing Bank Name','code' => 1], 422);
		$bankId = Input::get('bankId');
		// $ifsc = DmtBankBranch::where('dmt_bank_id',$bankId)
		// ->where('master_ifsc',1)->get();
		$headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json'
	    ];

	    $data = [
	      'dp_bank_id' => $bankId
	    ];

	     
	    $body = Unirest\Request\Body::json($data);

	  	$response = Unirest\Request::post(getenv('WS_URL').'/DMTService/BankBranchRequest', $headers, $body);


	    // $errorrem= new ErrorRmitter();
	    // $errorrem->request=json_encode($data);
	    // $errorrem->response=json_encode($response);
	    // $errorrem->save();

	  //dd($response);


	  if($response->code == 200)
		{  
            
          
		  if(isset($response->body->bank_branch))
		      {
		      	//dd($response->body);
                 $ifsc=$response->body;

		      } else
		      {
             	
                    $ifsc='' ;
		      }   
		if (! $ifsc) return Response::json(['message' => 'IFSC code not found.', 'code' => 2], 422);
		return Response::json($ifsc, 200);
	}
	}

	public function getAddBankBranchByIfsc ()
	{
		if (! Input::has('ifsc')) return Response::json(['message' => 'Missing IFSC Code', 'code' => 1], 422);
		$ifsc = Input::get('ifsc');
		$bankId = Input::get('bankId');

		//$branch = DmtBankBranch::where('ifsc', $ifsc)->where('dmt_bank_id',$bankId)->first();
		$headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json'
	    ];

	    $data = [
	     // 'dp_bank_id' => $bankId,
	      'ifsc_code'=>$ifsc
	    ];

	     
	    $body = Unirest\Request\Body::json($data);

	  	$response = Unirest\Request::post(getenv('WS_URL').'/DMTService/bankifsccoderequest', $headers, $body);


	    // $errorrem= new ErrorRmitter();
	    // $errorrem->request=json_encode($data);
	    // $errorrem->response=json_encode($response);
	    // $errorrem->save();

	  //dd($response);


	  if($response->code == 200)
		{  
            
          
		  	if(isset($response->body)){
			      	//dd($response->body);
	                $branch=$response->body;

			    } else
		      {
	         	
	              $branch='' ;
		      }   
		if (! $branch) return Response::json(['message' => 'IFSC is invalid or selected bank is invalid.', 'code' => 2], 422);
		return Response::json($branch, 200);
		}
		
	}
public function resendotp_ben($ben_id)
{

    if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
	$user = Auth::user();

$responsedata=explode('-', $ben_id);
if(isset($responsedata))
{

$beneficiary = $responsedata[1];
$remitterid =$responsedata[0];
}

    
/*Web services*/
		 $headers = [
		      'Accept' => 'application/json',
		      'Content-Type' => 'application/json'
		    ];

		     $data = [
		      'remitterid' =>(string)$remitterid,
		      'beneficiaryid' =>(string)$beneficiary,
		      'sessiontoken' =>\Cookie::get('user')
		       ];


		         
		     
		    $body = Unirest\Request\Body::json($data);

		  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/Beneficiaryresendotp', $headers, $body);
     //     $errorrem= new ErrorRmitter();
	    // $errorrem->request=json_encode($data);
	    // $errorrem->response=json_encode($response);
	    // $errorrem->save();
		  //dd($response);
		/*ENd web services*/


		    
		    if($response->body->status == 1)
		    {
		     	
		         return Redirect::back()->with('success','OTP Sent to Remitter  Mobile No  !!' );

		    }else
		    {
		    	
		   return Redirect::back()->with('error','OTP Not Send !!' );


		    
		      }   

	


}

public function resendotp_benvalidation($ben_id)
{

    if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
	$user = Auth::user();

$responsedata=explode('-', $ben_id);
if(isset($responsedata))
{

$remitterid = $responsedata[1];
$beneficiary =$responsedata[0];
}

    
/*Web services*/
		 $headers = [
		      'Accept' => 'application/json',
		      'Content-Type' => 'application/json'
		    ];

		     $data = [
		      'remitterid' =>(string)$remitterid,
		      'beneficiaryid' =>(string)$beneficiary,
		      'sessiontoken' =>\Cookie::get('user')
		       ];


		         
		     
		    $body = Unirest\Request\Body::json($data);

		  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/Beneficiaryresendotp', $headers, $body);
     //     $errorrem= new ErrorRmitter();
	    // $errorrem->request=json_encode($data);
	    // $errorrem->response=json_encode($response);
	    // $errorrem->save();
		  //dd($response);
		/*ENd web services*/


		    
		    if($response->body->status == 1)
		    {
		     	return Redirect::to('/remitter/'.$remitterid.'-'.$beneficiary.'/beneficiary/beneficiary_otp');
		        // return Redirect::back()->with('success','OTP Sent to Remitter  Mobile No  !!' );

		    }else
		    {
		    	
		   return Redirect::back()->with('error','OTP Not Send !!' );


		    
		      }   

	


}











public function pincode()
{
$datas=Input::all();
/*Web services*/
		 $headers = [
		      'Accept' => 'application/json',
		      'Content-Type' => 'application/json'
		    ];

		     $data = [
		     	
		      'pincode' =>(string)$datas[0]
		      
		       ];

		         
		     
		    $body = Unirest\Request\Body::json($data);

		  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/pincodeRequest', $headers, $body);
     //     $errorrem= new ErrorRmitter();
	    // $errorrem->request=json_encode($data);
	    // $errorrem->response=json_encode($response);
	    // $errorrem->save();
		 // dd($response);
if($response->code == 200)
{
 return Response::json(['data'=>$response->body], 200);
}else
{
 return Response::json(['error'=>'error'], 400);	
}
		 
		/*ENd web services*/

}





	public function postAddGST ()
	{
     if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
		//$user = Auth::user();
        $gst=Input::get('gst');
       
        /*Web service code*/
        $headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json'
	    ];

	     $data = [
	      'userid'=>(string)\Cookie::get('userid'),
	      'gst_no'=>$gst
	       ];

	    
	    $body = Unirest\Request\Body::json($data);

	  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/GstNumber', $headers, $body);

	    	if($response->body->status ==1)
	    	{
	    	
		      return Response::json(['message'=>$response->body->description,'status' =>1] , 200);
	     
	     	 }else
	      
	      	{

		      return Response::json(['message'=>$response->body->description,'status' =>0] , 422);

		    }

	}





}

?>