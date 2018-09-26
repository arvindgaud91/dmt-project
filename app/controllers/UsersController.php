<?php
use Acme\Auth\Auth;
use Acme\Helper\GateKeeper;
use Acme\SMS\SMS; 
/**
*
*/
class UsersController extends BaseController
{

	function __construct()
	{
		$this->beforeFilter('auth.json', ['only' => [
			'postUpdateStatus', 'postAddAdmin', 'postUpdateUserObj', 'postKYCDetails'
		]]);
		$this->beforeFilter('auth', ['only' => [
			'getAdmins', 'getAddAdmin', 'getUsers'
		]]);
	}

	public function getAdmins ()
	{
		GateKeeper::check(Auth::user(), ['superadmin-list-admins']);
		return View::make('admins.list');
	}

	public function getProfile($id)
	{	/*Webservice*/
		$headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json'
	    ];


	     $data = [
	      'user_id'=>$id
	       ];
	         
	     
	    $body = Unirest\Request\Body::json($data);

	  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/ProfileRequest', $headers, $body);
	  //dd(json_decode($response->raw_body));
	  	if($response->code == 200)

		{        
			      if(isset($response->raw_body))
			                  {
			                  	$respo['data']=$response->raw_body;
			                  }else
			                  {
			                  	$respo['data']='';
			                  }
			 
			 return View::make('users.profile')->with($respo);

		

	    }
		
		
		
	}

	public function getUpdatedBalance()
	{	

		if(! Input::get('user_id')) return Redirect::to('logout');
		/*Webservice*/
		
		$headers = [
			      'Accept' => 'application/json',
			      'Content-Type' => 'application/json'
			    ];

			     $data = [
			      'userid'=>(string)\Cookie::get('userid')
			       ];

			    $body = Unirest\Request\Body::json($data);

			  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/Updatedwalletbalancerequest', $headers, $body);

			   // $errorrem= new ErrorRmitter();
			   //  $errorrem->request=json_encode($data);
			   //  $errorrem->response=json_encode($response);
			   //  $errorrem->save();
			    if($response->body->response->status == 1)
			    {

			    	$user_type=Cookie::get('user_type');
			    	
			    	if($user_type==1){
			    		$sessiontoken=Cookie::get('user');
			    		if($response->body->response->sessiontoken!=$sessiontoken)
			    		return Response::json(['code'=>1] , 400);	
			    		//return Redirect::to('/logout');
			    	}
			    	

	            	return Response::json($response->body->response , 200);		
			    }
			  
		/*End webservices*/
		
	}


	public function getAddAdmin ()
	{
		GateKeeper::check(Auth::user(), ['s-u-a']);
		return View::make('admins.add');
	}

	public function postAddAdmin ()
	{
		GateKeeper::check(Auth::user(), ['a-u-c'], true);
		if (! Input::has('email')) {
			return Response::json(['message' => 'No email ID sent'], 422);
		}
		$duplicateUser = User::whereEmail(Input::get('email'))->first();
		if ($duplicateUser) {
			return Response::json(['message' => 'Email ID is already used'], 409);
		}
		// @todo validate input data
		$user = $this->setData(Input::except('permissions'), new User);
		$user->status = 1;
		$user->password = Hash::make('password');
		$user->save();
		if (count(Input::get('permissions', [])) > 0) {
			Permission::insert(array_map(function ($x) use($user) {
				return ['user_id' => $user->id, 'permission' => $x];
			}, Input::get('permissions')));
		}
		return $user;
	}

	public function postUpdateStatus ($userId)
	{
		GateKeeper::check(Auth::user(), ['s-u-c'], true);

		if (! Input::has('status')) {
			return Response::json(['message' => 'Incomplete request'], 400);
		}
		$user = User::find($userId);
		if (! $user) return Response::json(['message' => 'Resource not found'], 500);
		$user->status = Input::get('status');
		$user->save();
		return $user;
	}

	public function postUpdateUserObj ($userId)
	{
		$user = User::find($userId);
		if (! $user) return Response::json(['message' => 'User not found'], 500);
		// @todo: filter input, restrict access to back-office admins
		$user = $this->setData(Input::all(), $user);
		$user->save();
		return $user;
	}

	

	public function getUserDetails ($userId)
	{
		$user = User::find($userId);
		if (! $user) return Redirect::to('/')->with('message', 'User not found');
		return View::make('users.kyc')->with('profile_user', $user);
	}

	public function postKYCDetails ($userId)
	{
		$kyc = KYC::where('user_id', $userId)->first();
		if (! $kyc) $kyc = new KYC();
		$kyc = $this->setData(Input::all(), $kyc);
		$kyc->user_id = $userId;
		$kyc->save();
		Event::fire('kycdetails.updated', [$kyc]);
		return [];
	}

	public function getUsers ()
	{
		return View::make('users.list');
	}

	public function getCommissionsIndex ()
	{
		$vendors = User::getVendors();
		return View::make('commissions.index')
			->withVendors($vendors);
	}


	private function getVendorTypeById ($type)
	{
		$dict = [
        1 => [
            'id' => 1,
            'type' => 'agent',
            'parent_type_id' => 2,
            'parent' => 'distributor'
        ],
        2 => [
            'id' => 2,
            'type' => 'distributor',
            'parent_type_id' => 3,
            'parent' => 'super distributor'
        ],
        3 => [
            'id' => 3,
            'type' => 'super distributor',
        ],
        4 => [
            'id' => 4,
            'type' => 'sales executive',
        ],
        5 => [
            'id' => 5,
            'type' => 'area sales officeer',
        ],
        6 => [
            'id' => 6,
            'type' => 'area sales manager',
        ],
        7 => [
            'id' => 7,
            'type' => 'cluster head',
        ],
        10 => [
            'id' => 10,
            'type' => 'state head',
        ],
        11 => [
            'id' => 1,
            'type' => 'regional head',
        ],
      ];
      return $dict[$type];
	}

	public function getChangePasswordPage ($userId)
	{

		if ( ! Auth::user())
			return Redirect::to('/');
		return View::make('users.change-password');
	}

	public function postDebitWallet ($userId)
	{
		//dd(Input::all());
		if (! Input::has('amount')) 
			return Response::json(['code' => 1], 422);
		if (! is_int(Input::get('amount')) && ! is_double(Input::get('amount'))) 
			return Response::json(['code' => 1], 422);
		$headers = [
	    'Accept' => 'application/json',
	    'Content-Type' => 'application/json'
	  	];

	  	$users = Auth::user();
	  	$amount = Input::get('amount');
	  	$remarks = Input::get('remarks');

	  	$data = [
		    "amount"=>(string)$amount,
		    "user_type"=>(string)\Cookie::get('user_type'),
		    "request_type"=>"DD",
		    "user_id"=>(string)Input::get('child_id'),
		    "parent_id"=>(string)\Cookie::get('userid'),
		    "process_type"=>"1",
		    "remarks"=>$remarks
	    ];


	    $body = Unirest\Request\Body::json($data);

	    $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/TopupDircetCreditOrDeitRequest', $headers, $body);
	  //   $errorrem= new ErrorRmitter();
			// $errorrem->request=json_encode($data);
			// $errorrem->response=json_encode($response);
			// $errorrem->save();

			if($response->body->status == 1)
			{
				return Response::json('1', 200);
			}else
			{
				return Response::json('0', 402);
			}
	   //dd($response);
	}

	public function getDmtUsers($id){
		$tracker = Request::header('auth');
        $headers = [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json',
          'auth' => \Cookie::get('tracker')
        ];
        $body = \Unirest\Request\Body::json(['token' => $tracker]);
        $response = \Unirest\Request::post(getenv('AUTH_URL').'/api/auth/v1/users/actions/admin/verify', $headers, $body);

		$data = Vendor::where('user_id',$id)->first();
		//var_dump($data);
        return Response::json($data, 200);
	}
	   public function GetAepsUserNames($id)
	{
       $headers = [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json',
          'auth' => \Cookie::get('tracker')
        ];
        $idarray=array('id'=>$id);
		$body = ['user_ids' => $idarray];
        
    $response = \Unirest\Request::get(getenv('AUTH_URL').'/api/auth/v1/users', $headers, $body);

    if($response->body)
    {
	  return $response->body[0]->name;  

    }else
    {
    	return $id; 
    }
 
	}

	public function getAepsUserDetails($id)
    {
          $headers = [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json',
          'auth' => \Cookie::get('tracker')
        ];
    	$body = ['user_ids' => $id];
        
    	$response = \Unirest\Request::get(getenv('AUTH_URL').'/api/v1/user-detail-for-dmtsales', $headers, $body);
		//dd($response);
    	if($response->body)
    	{
		//dd($response->body);
      	return $response->body; 
    	}else
    	{
        	return $id; 
    	}
		//Add a comment to this line
    }

    public function changeDmtUserPassword($id)
    {
       
	 	$headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json'
	    ];

     	$data = [
	      'user_id' =>(string)$id,
	      'oldPassword' =>(string)Input::get('old_password'),
	      'newPassword' =>(string)Input::get('password'),
	      'confirmPassword'=>(string)Input::get('password_confirmation')
	    ];


		         
		     
		    $body = Unirest\Request\Body::json($data);

		  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/ChangePassword', $headers, $body);
		  //echo getenv('WS_URL').'/DMTService/ChangePassword';
		  //dd($response);
     //     $errorrem= new ErrorRmitter();
	    // $errorrem->request=json_encode($data);
	    // $errorrem->response=json_encode($response);
	    // $errorrem->save();
		 


		   if($response->body->status == 1)
		    {
		     	  
    			

		    	

		        return Response::json(['status' => 1,'description'=>$response->body->description], 200);

		    }else
		    {
		    	//dd($response->body->description);
		    	return Response::json(['status' => 2,'description'=>$response->body->description], 200);
		      //return Response::json(['datas'=>$response->body->description,'status'=>0], 200);


		    
		      }  
		/*ENd web services*/
    }

    public function getAgentBalance(){
    	$Balance=new BalanceTransfer;
        $Balance->setConnection('mysql1');

        $user = Auth::user();
        $Balances = $Balance->where('user_name', $user->dmt_vendor->bc_agent)->get(); 

        $datas= json_decode( json_encode($Balances), true);

        return View::make('users.balance-transfer',['datas' => $datas]);
        
    }

    public function postAgentBalance($user_name, $bal_id){

    	$Balance=new BalanceTransfer;
        $Balance->setConnection('mysql1');
        $Balances = $Balance->where('id', $bal_id)->get(); 
        $datas= json_decode( json_encode($Balances), true);
        $user = Auth::user();
        $vendor_balance =$user->dmt_vendor->balance;
        foreach ($datas as $data) {
        	$balance = $data['balance'];
        	$status = $data['status'];
        }
        if($status==1){
        	return Response::json(['message' => 'Please upload Address Proof file!'], 401); 
        }
        //Update DMT Vendors Balance
        $total = $balance + $vendor_balance;
        $vendorSubmitted = ['balance'=>$total];
        $vendor = Vendor::where('bc_agent', $user_name)->update($vendorSubmitted);

        //Insert Wallet Transaction
        $wallet_transaction = new WalletTransaction;
        $wallet_transaction->user_id = $user->dmt_vendor->user_id;
        $wallet_transaction->transaction_type = 1;
        $wallet_transaction->amount = $balance;
        $wallet_transaction->balance = $total;
        $wallet_transaction->activity = 'Credit-Wallet';
        $wallet_transaction->narration = 'Received from old portal to new portal';
        $wallet_transaction->save();

        // Update Status of Balnce Transfer Table
        $status = 1;
        $balanceSubmitted = ['status'=>$status];
        $updateBalance = $Balance->where('id', $bal_id)->update($balanceSubmitted);

        return Response::json('Balance Updated Successfully!', 200);
    	
    }

    public function smsUserBalance(){
		$userObj = DB::table('dmt_vendors')->where('balance', '>=', 500000);
       	$users = $userObj->get();
       	if($users < 0){
       		$id    = array();
	       	foreach ($users as $user) {
	       		$balance = $user->balance;

	       		$id[] = $user->user_id . ' - ' . $user->balance;
	       		$bc_agent = $user->bc_agent;
	       		
	       		$vendor_submitted = [
	            	'bc_agent'=>$bc_agent."t"
	        	];
	       		$users_update = Vendor::where('user_id', $user->user_id)->update($vendor_submitted);
	       	}
	       	$ids = implode("'\r\n'", $id);
	       	$msg = "'".$ids."'";
	       	$phone_no = '9004835502';
	       	SMS::send($phone_no, "Dear admin below users having balance more than 5 Lakhs. \r\nUser ID - Amount\r\n".$msg.".");
	       	echo 'Message Sent';
       	}
    }

    public function smsUserClosingBalance(){
		$agentObj = DB::table('dmt_vendors')
            ->select(DB::raw('SUM(balance) as balance'))
            ->where('type', 1)
            ->whereNotIn('user_id', array(311));
       	$agents = $agentObj->get();
		$agent_closing_balance = number_format($agents[0]->balance, 2);
		$distObj = DB::table('dmt_vendors')
            ->select(DB::raw('SUM(balance) as balance'))
            ->where('type', 2)
            ->whereNotIn('user_id', array(13));
       	$dists = $distObj->get();
		$dist_closing_balance = number_format($dists[0]->balance, 2);
		$superDistObj = DB::table('dmt_vendors')
            ->select(DB::raw('SUM(balance) as balance'))
            ->where('type', 3)
            ->whereNotIn('user_id', array(13));
       	$super_dists = $superDistObj->get();
		$super_dist_closing_balance = number_format($super_dists[0]->balance, 2);
       	$phone_no = '9004835502';
       	SMS::send($phone_no, "Agent Closing Balance\r\nRs. ".$agent_closing_balance." \r\n\r\nDistributor Closing Balance\r\nRs. ".$dist_closing_balance."\r\n\r\nSuper Distributor Closing Balance\r\nRs. ".$super_dist_closing_balance."");
       	echo 'Message Sent';
    }

}



