<?php
use Acme\Auth\Auth;
use Acme\Helper\GateKeeper;
use Acme\Helper\Export;
class WalletsController extends BaseController
{

  // @TODO set up transactions for atomicity

  public function getBalanceRequest()
  {
    if (! Auth::user()) return Redirect::to('/');
    return View::make('wallets.balance-request');
  }



 public function getBalanceRequestTEST()
  {
    if (! Auth::user()) return Redirect::to('/');
    return View::make('wallets.balance-requests');
  }
  // public function getBalanceRequests($status)
  // {
  //   $tracker = Request::header('auth');
  //   $headers = [
  //     'Accept' => 'application/json',
  //     'Content-Type' => 'application/json',
  //     'auth' => \Cookie::get('tracker')
  //   ];
  //   $body = \Unirest\Request\Body::json(['token' => $tracker]);
  //   $response = \Unirest\Request::post(getenv('AUTH_URL').'/api/auth/v1/users/actions/admin/verify', $headers, $body); 
  //   if (! $response->body) return Response::json(['message' => 'Unauthorized access.'], 401);
  //   $requests = BalanceRequest::where('status', $status)->get();
  //   return Response::json($requests, 200);
  // }


  public function getBalanceRequests($status)
  {
    $tracker = Request::header('auth');
    $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json',
      'auth' => \Cookie::get('tracker')
    ];
    $body = \Unirest\Request\Body::json(['token' => $tracker]);
    $response = \Unirest\Request::post(getenv('AUTH_URL').'/api/auth/v1/users/actions/admin/verify', $headers, $body); 
    if (! $response->body) return Response::json(['message' => 'Unauthorized access.'], 401);
    
    // if($status==3){
    //   $requests = BalanceRequest::paginate(1);
    // }else{
    //   $requests = BalanceRequest::where('status', $status)->paginate(1);
    // }
   
    $queryObj= new BalanceRequest;
    if(Input::has('user_id'))
      $queryObj=$queryObj->where('user_id',Input::get('user_id'));

    if($status!=3)
       $queryObj=$queryObj->where('status', $status);
    
     if($status!=0)
       $queryObj=$queryObj->orderBy('id', 'DESC');

     $requests=$queryObj->paginate(100);
    return Response::json($requests, 200);
  }
  
  public function exportBalanceRequests($status)
  {
    $tracker = Request::header('auth');
    $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json',
      'auth' => \Cookie::get('tracker')
    ];
    $body = \Unirest\Request\Body::json(['token' => $tracker]);
    $response = \Unirest\Request::post(getenv('AUTH_URL').'/api/auth/v1/users/actions/admin/verify', $headers, $body); 
    if (! $response->body) return Response::json(['message' => 'Unauthorized access.'], 401);

    $queryObj= BalanceRequest::whereBetween('created_at', [Input::get('start_date_time'), Input::get('end_date_time')]);

    if(Input::has('user_id'))
      $queryObj=$queryObj->where('user_id',Input::get('user_id'));

    if($status!=3)
       $queryObj=$queryObj->where('status', $status);
    
    $requests = $queryObj->get();
    return Response::json($requests, 200);
  }


  public function postBalanceRequest()
  {
    /*Webservices*/
      $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
      ];
      $transferMode=Input::get('transfer_mode');
      if( $transferMode!=6){
        $destinationPath =  'upload/bank_recp';
          $file=Input::file('file');
          $kyc_pan_card = \Cookie::get('userid').time(). '_Bank_Receipt.' . $file->getClientOriginalExtension();
          $file->move($destinationPath . '/', $kyc_pan_card);
        }else{
          $kyc_pan_card='default.jpg';
        }
      
       

       $data = [
        'amount'=>Input::get('amount'),
        'transfer_mode'=>Input::get('transfer_mode'),
        'bank'=>Input::get('bank'),
        'branch'=>Input::get('branch'),
        'referenceno'=>Input::get('reference_number'),
        'user_id' => (string)\Cookie::get('userid'),
        'parent_id' =>'879',
        'remarks'=>Input::get('reference_number'),
        'ref_doc'=>'upload/bank_recp/'.$kyc_pan_card,
        'process_type'=>'1', //1-DMT, 2- AEPS
        'request_type'=>'1' // 1 - Request to admin , 2 - Request to dist
         ];
          
      $body = Unirest\Request\Body::json($data);

    $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/TopupRequest', $headers, $body);
//dd($response);
    $errorrem= new ErrorRmitter();
      $errorrem->request=json_encode($data);
      $errorrem->response=json_encode($response);
      $errorrem->save();

if($response->body->status ==1)
{
return Response::json(['message'=>'success'] , 200);
}else
{
return Response::json(['message'=>$response->body->description] , 422);
}
    /*End webservices*/

   
  }

  public function getBalanceRequestFromDistributor ()
  {
    // GateKeeper::checkRoles(Auth::user(), 1);
    
    $parent = \Cookie::get('parentid');
    return View::make('wallets.from-distributor')
      ->withParent($parent);
  }

  public function getIncomingBalanceRequestByParentVendor ($id)
  {
      if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
    $user = Auth::user();
//dd($user);

    $current_page=Input::get('page') ? Input::get('page') : "1";
    $record=1;
    $per_page=0;
    $total=0;
  $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
      ];

      $data = [
         'user_type'=>'2',
         'user_id'=>(string)\Cookie::get('userid'),
          "currentpage"=>$current_page,
          "per_page"=>"100"
                  
      ];

    $body = Unirest\Request\Body::json($data);

    $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/TopupPendingList', $headers, $body);
    // //$response = Unirest\Request::post(getenv('WS_URL').'/DMTService/Requesttransactionreport', $headers, $body);
    //  $errorrem= new ErrorRmitter();
    //   $errorrem->request=json_encode($data);
    //   $errorrem->response=json_encode($response);
    //   $errorrem->save();
    
    $record=1;
            $per_page=0;
            $total=0;
            $current_page=0;

    if($response->body->status == 0)
    {
      $data['requests']='';
       return View::make('wallets.incoming-request',['requests'=>$data,'per_page'=>$per_page,'total'=>$total,'current_page'=>$current_page,'record'=>$record]);
     }else
     {

       $response_data=json_decode($response->raw_body);
      $per_page=$response_data->per_page;
                $total=$response_data->total;
                $current_page=$response_data->current_page;
    foreach ($response_data->requestList as $requests) {

      $request_data[] = json_decode($requests);
    }
     
      return View::make('wallets.incoming-request',['requests'=>$request_data,'per_page'=>$per_page,'total'=>$total,'current_page'=>$current_page,'record'=>$record]);
     }
   
  }

  public function getIncomingBalanceRequestsByStatus ($id)
  {


    

    $balance_request_vendor_data = new BalanceRequestVendor;
    $balance_request_vendor_data->setConnection('mysql2');

    $userIds = $balance_request_vendor_data->where('parent_id', $id)->where('status', $this->getIncomingBalanceRequestStatus(Input::get('status')))->lists('user_id');
    $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json',
      'auth' => \Cookie::get('tracker')
    ];
    $body = ['user_ids' => $userIds];
    $response = \Unirest\Request::get(getenv('AUTH_URL').'/api/auth/v1/users', $headers, $body);
    $users = $response->body;
    $data['requests'] = $balance_request_vendor_data->where('parent_id', $id)->where('status', $this->getIncomingBalanceRequestStatus(Input::get('status')))->get();
    $data['requests'] = array_map(function ($req) use ($users)
    {
      foreach ($users as $user) {
        if ($user->id == $req->user_id)
          $req->user = $user;
      }
      return $req;
    }, json_decode($data['requests']));
    return $data['requests'];
  }

  private function getIncomingBalanceRequestStatus ($status)
  {
    $dict = [
      "pending" => 0,
      "approved" => 1,
      "rejected" => 2
    ];
    return $dict[$status];
  }

  public function getCreditWalletRequest ($id)
  {
    
    $child_import_data=(explode("-",$id,2));
  

    if (! Auth::user()) return Redirect::to('/');
   
      $child['name'] = $child_import_data[1];//$response->body;

    $child_data['id']=base64_decode($child_import_data[0]);//Vendor::where('user_id',$id)->first();
   
    if((\Cookie::get('user_type')==3) || (\Cookie::get('user_type')==2)){
       return View::make('wallets.credit-request',['child'=>$child,'child_data'=>$child_data,'parent_data'=>Cookie::get('userid')]);
    }
    else{
      return Redirect::to('/');
    }
  }

   public function GetAepsUserNames ($id)
  {
        $headers = [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json',
          'auth' => \Cookie::get('tracker')
        ];
        $idarray=array('id'=>$id);
        $body = ['user_ids' => $idarray]; 
        $response = \Unirest\Request::get(getenv('AUTH_URL').'/api/auth/v1/users', $headers, $body);
      //dd($response);
      if($response->body)
        {
        //dd($response->body[0]);
        return $response->body[0]->name.'/'.$response->body[0]->phone_no; 
       }
       else
      {
        return $id; 
      }
  }


  public function getDebitWalletRequest ($id)
  {

    $child_import_data=(explode("-",$id,2));
  

    if (! Auth::user()) return Redirect::to('/');
   
      $child['name'] = $child_import_data[1];

    $child_data['id']=base64_decode($child_import_data[0]);
   //dd($child_data);
    if((\Cookie::get('user_type')==3) || (\Cookie::get('user_type')==2)){
       return View::make('wallets.debit-request',['child'=>$child,'child_data'=>$child_data,'parent_data'=>Cookie::get('userid')]);
    }
    else{
      return Redirect::to('/');
    }
  }

  public function postApproveBalanceRequestByDistributor ($id)
  {

    /*Webservices*/
    $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];


     $data = [
      'request_id' => $id,
      'approval_status'=>'A'
       ];
         
     
    $body = Unirest\Request\Body::json($data);

  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/TopupRequestApproval', $headers, $body);

//dd($response);

// $errorrem= new ErrorRmitter();
//       $errorrem->request=json_encode($data);
//       $errorrem->response=json_encode($response);
//       $errorrem->save();


      if($response->body->status==1)
      {
return Response::json(['message' => 'Success', 'code' => 1], 200); 

      }else
      {
        return Response::json(['message' => $response->body->description, 'code' => 1], 422); 
      }

    /*End webservices*/
    // GateKeeper::checkRoles(Auth::user(), 2, true);
    // try
    // {
    //     DB::beginTransaction();

    //     $request = BalanceRequestVendor::find($id);
    //     $userIds = [$request->user_id];
    //     $headers = [
    //       'Accept' => 'application/json',
    //       'Content-Type' => 'application/json',
    //       'auth' => \Cookie::get('tracker')
    //     ];
    //     $body = ['user_ids' => $userIds];
    //     $response = \Unirest\Request::get(getenv('AUTH_URL').'/api/auth/v1/users', $headers, $body);

    //     $user = $response->body[0];
    //     if ($request->parent_id != Auth::user()->id)
    //       return Response::json(['message' => 'Vendor is not assigned to you.', 'code' => 2], 403);

    //     $vendor = DmtVendor::where('user_id', $request->parent_id)->lockForUpdate()->first();
    //     if ($vendor->balance < $request->amount) return Response::json(['message' => 'Insufficient balance.', 'code' => 1], 422);

    //     $vendor->balance -= $request->amount;
    //     $vendor->save();
    //     $agent = DmtVendor::where('user_id', $request->user_id)->lockForUpdate()->first();
    //     $aepsname=$this->GetAepsUserNames($vendor->user_id);
    //     $debit = WalletTransaction::create(['user_id' => $vendor->user_id, 'transaction_type' => 0,'activity'=>'Debit-Request','narration'=>'Transfer To -'.$this->GetAepsUserNames($agent->user_id) ,'amount' => $request->amount, 'balance' => $vendor->balance]);

        
    //     $agent->balance += $request->amount;
    //     $agent->save();
    //     $credit = WalletTransaction::create(['user_id' => $agent->user_id, 'transaction_type' => 1,'activity'=>'Credit-Request','narration'=>'Received From -'.$aepsname.'-'.$vendor->user_id ,'amount' => $request->amount, 'balance' => $agent->balance]);

    //     $request->status = 1;
    //     $request->save();

    //     WalletAction::create([
    //       'user_id' => $agent->user_id,
    //       'counterpart_id' => $vendor->user_id,
    //       'amount' => $request->amount,
    //       'status' => 1,
    //       'credit_id' => $credit->id,
    //       'wallet_request_id' => $request->id,
    //       'type' => 0,
    //       'admin' => false,
    //       'automatic' => false
    //     ]);
    //     WalletAction::create([
    //       'user_id' => $vendor->user_id,
    //       'counterpart_id' => $agent->user_id,
    //       'amount' => $request->amount,
    //       'status' => 1,
    //       'debit_id' => $debit->id,
    //       'wallet_request_id' => $request->id,
    //       'type' => 0,
    //       'admin' => false,
    //       'automatic' => false
    //     ]);

    //     // Commisison Transactions
    //     $commission_rates=($request->parent_id==3971) ? (0.60):(0.42);
    //     $agent = DmtVendor::where('user_id', $request->user_id)->lockForUpdate()->first();
    //     $commission = $request->amount*($commission_rates/100);
    //     $agent->balance -= $commission;
    //     $agent->save();
    //     $vendor = DmtVendor::where('user_id', $request->parent_id)->lockForUpdate()->first();
    //    $debit_commission = WalletTransaction::create(['user_id' => $agent->user_id, 'transaction_type' => 0,'activity'=>'Debit','narration'=>'Transfer To -'.$this->GetAepsUserNames($vendor->user_id),'amount' => $commission, 'balance' => $agent->balance]);

        
    //     $vendor->balance += $request->amount*($commission_rates/100);
    //     $vendor->save();
    //     $credit_commission = WalletTransaction::create(['user_id' => $vendor->user_id, 'transaction_type' => 1,'activity'=>'Credit','narration'=>'Received From -'.$this->GetAepsUserNames($agent->user_id),'amount' => $commission, 'balance' => $vendor->balance]);
      
    //     WalletAction::create([
    //       'user_id' => $vendor->user_id,
    //       'counterpart_id' => $agent->user_id,
    //       'amount' => $request->amount,
    //       'status' => 1,
    //       'credit_id' => $credit_commission->id,
    //       'wallet_request_id' => $request->id,
    //       'type' => 0,
    //       'admin' => false,
    //       'automatic' => false,
    //       'commission' => true
    //     ]);

    //     WalletAction::create([
    //       'user_id' => $agent->user_id,
    //       'counterpart_id' => $vendor->user_id,
    //       'amount' => $request->amount,
    //       'status' => 1,
    //       'debit_id' => $debit_commission->id,
    //       'wallet_request_id' => $request->id,
    //       'type' => 0,
    //       'admin' => false,
    //       'automatic' => false,
    //       'commission' =>true
    //     ]);

    //     // Event::fire('vendorBalanceRequest:committed', [['request_id' => $request->id, 'user_id' => Auth::user()->id, 'type' => 3, 'status' => 1]]);

    //     DB::commit();
    //     return Response::json($request, 200);
    // } catch (\Exception $e) {
    //     DB::rollBack();  
    //     return Response::json(['message' => 'Something went wrong', 'code' => 1], 422);           
    // }
  }

  public function postRejectBalanceRequestByDistributor ($id)
  {
    // // GateKeeper::checkRoles(Auth::user(), 2, true);

    // if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.']);
    // $user = Auth::user();
    // if ($user->type != 4 || ! in_array('dmt', $user->permissions) || $user->vendorDetails->type != 2) 
    //   return Response::json(['message' => 'Unauthorized access'], 401);

    // $request = BalanceRequestVendor::find($id);
    // $request->status = 2;
    // $request->save();
    // // Event::fire('vendorBalanceRequest:committed', [['request_id' => $request->id, 'user_id' => Auth::user()->id, 'type' => 3, 'status' => 2]]);

    // return Response::json($request, 200);
    /*Webservices*/
    $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];


     $data = [
      'request_id' => $id,
      'approval_status'=>'R'
       ];
         
     
    $body = Unirest\Request\Body::json($data);
//dd($body);
  $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/TopupRequestApproval', $headers, $body);
//dd($response);
// $errorrem= new ErrorRmitter();
//       $errorrem->request=json_encode($data);
//       $errorrem->response=json_encode($response);
//       $errorrem->save();

if($response->body->status == 1)
{
return Response::json(['message' => 'Success', 'code' => 1], 200); 
}else
{
return Response::json(['message' => 'Error', 'code' => 1], 200); 
}



  }


  public function postBalanceRequestFromDistributors ()
  {
    // GateKeeper::checkRoles(Auth::user(), 1, true);
    // if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.']);
    // $user = Auth::user();
    // $parent_id=DmtVendor::where('user_id',Auth::user()->id)->pluck('parent_id');
    // if ($user->type != 4 || ! in_array('dmt', $user->permissions)) 
    //   return Response::json(['message' => 'Unauthorized access'], 401);

    // if(! Input::has('amount'))
    //   return Response::json(['code' => 1], 422);

    // $requestObj = array_merge($this->filterOnly(Input::all(), ['remarks','amount']), ['user_id' => Auth::user()->id, 'parent_id' => $parent_id]);
    // $balanceRequest = new BalanceRequestVendor($requestObj);
    // $balanceRequest->save();
    // return [];
     $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
      ];

       $data = [
        'user_id' => (string)\Cookie::get('userid'),
        'parent_id'=>(string)\Cookie::get('parentid'),
        'amount'=>(string)Input::get('amount'),
        'remarks'=>(string)Input::get('remarks'),
        'process_type'=>'1', //1-DMT, 2- AEPS
        'request_type'=>'2' // 1 - Request to admin , 2 - Request to dist
         ];

       
      $body = Unirest\Request\Body::json($data);

    $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/TopupRequest', $headers, $body);
      // $errorrem= new ErrorRmitter();
      //     $errorrem->request=json_encode($data);
      //     $errorrem->response=json_encode($response);
      //     $errorrem->save();
    //dd($response);

    if($response->body->status == 1)
    {
   return Response::json(['message'=>'success'] , 200);
    }else
    {
return Response::json(['message'=>'error'] , 400);
    }
  }


  public function postCreditWalletRequest ($id)
  {
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
        "request_type"=>"DC",
        "user_id"=>(string)Input::get('child_id'),
        "parent_id"=>(string)\Cookie::get('userid'),
        "process_type"=>"1",
        "remarks"=>$remarks
      ];


      $body = Unirest\Request\Body::json($data);

      $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/TopupDircetCreditOrDeitRequest', $headers, $body);
      // $errorrem= new ErrorRmitter();
      // $errorrem->request=json_encode($data);
      // $errorrem->response=json_encode($response);
      // $errorrem->save();
      if($response->body->status == 1)
      {
        return Response::json(['description'=>$response->body->description,'status'=>1], 200);
      }else
      {
        return Response::json(['description'=>$response->body->description,'status'=>0], 200);
      }
  }
  public function postReceivedRequest ()
  {

    //GateKeeper::checkRoles(Auth::user(), [9], true);
    // $tracker = Request::header('auth');
    // $headers = [
    //   'Accept' => 'application/json',
    //   'Content-Type' => 'application/json',
    //   'auth' => \Cookie::get('tracker')
    // ];
    // $body = \Unirest\Request\Body::json(['token' => $tracker]);
    // $response = \Unirest\Request::post(getenv('AUTH_URL').'/api/auth/v1/users/actions/admin/verify', $headers, $body); 
    //if(isset($response->body->message)) return Response::json(['message' => 'Unauthorized access.'], 401);;
    // if (! $response->body) return Response::json(['message' => 'Unauthorized access.'], 401);

   // if ($response->body->message=="Missing important info.") return Response::json(['message' => 'Unauthorized access.'], 401);
     //Input::get('user_amount'));
      $users_amount=Input::get('user_amount');
      $users_ids=Input::get('transaction_user_ids');
      // foreach ($users_amount as $key => $value) {

      //   $vendor = Vendor::where('user_id',  $users_ids[$key])->lockForUpdate()->first();

      //   if(!$vendor)
      //     return Response::json(['code' => 3], 422);

      //   if (! $users_amount[$key])
      //     return Response::json(['code' => 1], 422);

        
        
      //   /*if ($vendor->balance < Input::has('transaction_amount') || ($vendor->balance - Input::has('transaction_amount'))<100) return Response::json(['code' => 2], 422);
      //     */
      //   $vendor->balance +=  $users_amount[$key];
      //   $vendor->save();

      //   $credit = WalletTransaction::create(['user_id' => $vendor->user_id, 'transaction_type' => 1, 'amount' =>$users_amount[$key], 'balance' =>$vendor->balance,'activity'=>'credited','narration'=>'Received from aeps']);

      //   DmtWalletAction::create([
      //     'user_id' => $vendor->user_id,
      //     'amount' =>  $users_amount[$key],
      //     'status' => 1,
      //     'credit_id' => $credit->id,
      //     'transaction_type' =>1,
      //     'remarks' => 'Received from aeps'
      //   ]);
        
      //   //$tolat_commission= Input::get('transaction_amount')*(.42);
      //   if($vendor->type==1){
      //     $admin_commission= ( $users_amount[$key]*(.22))/100;
      //     $distributor_commission=( $users_amount[$key]*(.20))/100;
      //     $tolat_commission=($admin_commission+$distributor_commission);
      //   }
      //   if($vendor->type==2){
      //     $admin_commission= ( $user_amount[$key]*(.22))/100;
      //     $tolat_commission=$admin_commission;
      //   }

      //   /* Commission Debit entry*/
      //   $vendor = Vendor::where('user_id', $users_ids[$key])->lockForUpdate()->first();
      //   if ($vendor->balance < $tolat_commission) return Response::json(['code' => 2], 422);
          
      //   $vendor->balance -= $tolat_commission;
      //   $vendor->save();
      //   $debit = WalletTransaction::create(['user_id' => $vendor->user_id, 'transaction_type' => 0, 'amount' => $tolat_commission, 'balance' =>$vendor->balance,'activity'=>'debited','narration'=>'commission debited']);

      //   DmtWalletAction::create([
      //     'user_id' => $vendor->user_id,
      //     'amount' =>  $tolat_commission,
      //     'status' => 1,
      //     'debit_id' => $debit->id,
      //     'transaction_type' =>0,
      //     'remarks' => 'Commission deduction'
      //   ]);
      //   if($vendor->type==1){
      //    /*Commission Credit entry in distributor account*/
      //   $vendor_parent = Vendor::where('user_id',  $users_ids[$key])->pluck('parent_id');
      //   $parent_details = Vendor::where('user_id', $vendor_parent)->lockForUpdate()->first();
       
      //   $parent_details->balance += $distributor_commission;
      //   $parent_details->save();
      //   $parent_credit = WalletTransaction::create(['user_id' => $parent_details->user_id, 'transaction_type' => 1, 'amount' =>$distributor_commission, 'balance' =>$parent_details->balance,'activity'=>'credited','narration'=>'commission credited']);

      //   DmtWalletAction::create([
      //     'user_id' => $parent_details->user_id,
      //     'amount' => $distributor_commission,
      //     'status' => 1,
      //     'credit_id' => $parent_credit->id,
      //     'transaction_type' =>1,
      //     'remarks' => 'Received commission from'.$users_ids[$key]
      //   ]);
      //   }
        

      //    /*Commission Credit entry in admin account*/
      //    $admin_details = Vendor::where('user_id', 1)->lockForUpdate()->first();
       
      //   $admin_details->balance += $admin_commission;
      //   $admin_details->save();
      //   $admin_credit = WalletTransaction::create(['user_id' => $admin_details->user_id, 'transaction_type' => 1, 'amount' =>$admin_commission, 'balance' =>$admin_details->balance,'activity'=>'credited','narration'=>'commission credited']);

      //   DmtWalletAction::create([
      //     'user_id' => $admin_details->user_id,
      //     'amount' => $admin_commission,
      //     'status' => 1,
      //     'credit_id' => $admin_credit->id,
      //     'transaction_type' =>1,
      //     'remarks' => 'Received commission from'.$users_ids[$key]
      //   ]);
      // }
    // dd('fff');
      //dd(Input::all());
        $vendor = Vendor::where('user_id',  $users_ids)->lockForUpdate()->first();

        if(!$vendor)
          return Response::json(['code' => 3], 422);

        if (! $users_amount)
          return Response::json(['code' => 1], 422);

        
        
        /*if ($vendor->balance < Input::has('transaction_amount') || ($vendor->balance - Input::has('transaction_amount'))<100) return Response::json(['code' => 2], 422);
          */
        $vendor->balance +=  $users_amount;
        $vendor->save();

        $credit = WalletTransaction::create(['user_id' => $vendor->user_id, 'transaction_type' => 1, 'amount' =>$users_amount, 'balance' =>$vendor->balance,'activity'=>'credited','narration'=>'Received from aeps']);

        DmtWalletAction::create([
          'user_id' => $vendor->user_id,
          'amount' =>  $users_amount,
          'status' => 1,
          'credit_id' => $credit->id,
          'transaction_type' =>1,
          'remarks' => 'Received from aeps'
        ]);
        
        //$tolat_commission= Input::get('transaction_amount')*(.42);
        if($vendor->type==1){
          $admin_commission= ( $users_amount*(.22))/100;
          $distributor_commission=( $users_amount*(.20))/100;
          $tolat_commission=($admin_commission+$distributor_commission);
        }
        if($vendor->type==2){
          $admin_commission= ( $user_amount*(.22))/100;
          $tolat_commission=$admin_commission;
        }

        /* Commission Debit entry*/
        $vendor = Vendor::where('user_id', $users_ids)->lockForUpdate()->first();
        if ($vendor->balance < $tolat_commission) return Response::json(['code' => 2], 422);
          
        $vendor->balance -= $tolat_commission;
        $vendor->save();
        $debit = WalletTransaction::create(['user_id' => $vendor->user_id, 'transaction_type' => 0, 'amount' => $tolat_commission, 'balance' =>$vendor->balance,'activity'=>'debited','narration'=>'commission debited']);

        DmtWalletAction::create([
          'user_id' => $vendor->user_id,
          'amount' =>  $tolat_commission,
          'status' => 1,
          'debit_id' => $debit->id,
          'transaction_type' =>0,
          'remarks' => 'Commission deduction'
        ]);
        if($vendor->type==1){
         /*Commission Credit entry in distributor account*/
        $vendor_parent = Vendor::where('user_id',  $users_ids)->pluck('parent_id');
        $parent_details = Vendor::where('user_id', $vendor_parent)->lockForUpdate()->first();
       
        $parent_details->balance += $distributor_commission;
        $parent_details->save();
        $parent_credit = WalletTransaction::create(['user_id' => $parent_details->user_id, 'transaction_type' => 1, 'amount' =>$distributor_commission, 'balance' =>$parent_details->balance,'activity'=>'credited','narration'=>'commission credited']);

        DmtWalletAction::create([
          'user_id' => $parent_details->user_id,
          'amount' => $distributor_commission,
          'status' => 1,
          'credit_id' => $parent_credit->id,
          'transaction_type' =>1,
          'remarks' => 'Received commission from'.$users_ids
        ]);
        }
        

         /*Commission Credit entry in admin account*/
         $admin_details = Vendor::where('user_id', 1)->lockForUpdate()->first();
       
        $admin_details->balance += $admin_commission;
        $admin_details->save();
        $admin_credit = WalletTransaction::create(['user_id' => $admin_details->user_id, 'transaction_type' => 1, 'amount' =>$admin_commission, 'balance' =>$admin_details->balance,'activity'=>'credited','narration'=>'commission credited']);

        DmtWalletAction::create([
          'user_id' => $admin_details->user_id,
          'amount' => $admin_commission,
          'status' => 1,
          'credit_id' => $admin_credit->id,
          'transaction_type' =>1,
          'remarks' => 'Received commission from'.$users_ids
        ]);
      
    return Response::json(['code'=>4], 200);
  }

  public function GetAepsUserNamesForAdmin ($id)
  {
        $headers = [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json',
          'auth' => \Cookie::get('tracker')
        ];
        $idarray=array('id'=>$id);
        $body = ['user_ids' => $idarray]; 
        $response = \Unirest\Request::get(getenv('AUTH_URL').'/api/v1/user-detail-for-admin', $headers, $body);
        //dd($response);
      if($response->body)
        {
        //dd($response->body[0]->name);
        return $response->body[0]->name;  
       }
       else
      {
        return $id; 
      }
  }

  public function postApproveBalanceRequestByAdmin ($id)
  {
    $tracker = Request::header('auth');
    $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];
    $body = \Unirest\Request\Body::json(['token' => $tracker]);
    $response = \Unirest\Request::post(getenv('AUTH_URL').'/api/auth/v1/users/actions/admin/verify', $headers, $body); 
    if (! $response->body) return Response::json(['message' => 'Unauthorized access.'], 401);
    $admin_id = $response->body;
    $request = BalanceRequest::where('id', $id)->first();
   

    // Credit DIPL Wallet with request amount.
    $dipl_vendor = DmtVendor::where('type', 8)->lockForUpdate()->first();
    //dd($dipl_vendor->user_id);
    //dd($dipl_vendor);
     //@TODO: provide for multiple dipl_vendors
    $dipl_vendor->balance += $request->amount;
    $dipl_vendor->save();
    $aepsname=$this->GetAepsUserNamesForAdmin($dipl_vendor->user_id);
    $dipl_creditTx = WalletTransaction::create(['user_id' => $dipl_vendor->user_id, 'transaction_type' => 1, 'amount' => $request->amount, 'balance' => $dipl_vendor->balance,'activity' => 'Credit-Request','narration'=>'Transfer To -'.$aepsname]);
    $walletAction = WalletAction::create([
      'user_id' => $dipl_vendor->user_id,
      'counterpart_id' => 0,
      'amount' => $request->amount,
      'remarks' => '',
      'status' => 1,
      'credit_id' => $dipl_creditTx->id,
      'type' => 0,
      'admin' => true,
      'automatic' => false,
      'commission' => false,
      'admin_id' => $admin_id
    ]);

    // Debit DIPL wallet with request amount
    $dipl_vendor = DmtVendor::where('type', 8)->lockForUpdate()->first(); //@TODO: provide for multiple dipl_vendors
    $dipl_vendor->balance -= $request->amount;
    $dipl_vendor->save();
    $dipl_debitTx = WalletTransaction::create(['user_id' => $dipl_vendor->user_id, 'transaction_type' => 0, 'amount' => $request->amount, 'balance' => $dipl_vendor->balance,'activity' => 'Debit-Request','narration'=>'Received From -'.$aepsname]);


    $walletAction = WalletAction::create([
      'user_id' => $dipl_vendor->user_id,
      'counterpart_id' => $request->user_id,
      'amount' => $request->amount,
      'remarks' => '',
      'status' => 1,
      'debit_id' => $dipl_debitTx->id,
      'type' => 0,
      'admin' => true,
      'automatic' => false,
      'commission' => false,
      'admin_id' => $admin_id
    ]);

    // Credit vendor wallet with request amount.
    $vendor = DmtVendor::where('user_id', $request->user_id)->lockForUpdate()->first();
    $vendor->balance += $request->amount;
    $vendor->save();

    // update wallet_transactions
    $creditTx = WalletTransaction::create(['user_id' => $request->user_id, 'transaction_type' => 1, 'amount' => $request->amount, 'balance' => $vendor->balance, 'activity' => 'Credit-Request','narration'=>'Transfer To -'.$this->GetAepsUserNamesForAdmin($request->user_id)]);

    $walletAction = WalletAction::create([
      'user_id' => $request->user_id,
      'counterpart_id' => $dipl_vendor->user_id,
      'amount' => $request->amount,
      'remarks' => '',
      'status' => 1,
      'credit_id' => $creditTx->id,
      'type' => 0,
      'admin' => true,
      'automatic' => false,
      'commission' => false,
      'admin_id' => $admin_id
    ]);

    // Allocate Commissions
    $commissionRate = $vendor->type == 1 ? ($vendor->parent_id == 3971 ? 0.60 : 0.42) : ($vendor->type == 2 ? ($vendor->user_id == 3971 ? 0.25 : 0.22) : 0);
    $commission = $request->amount*($commissionRate/100);
    $vendor = DmtVendor::where('user_id', $request->user_id)->lockForUpdate()->first();
    $vendor->balance -= $commission;
    $vendor->save();

    $commissionDebitTx = WalletTransaction::create(['user_id' => $request->user_id, 'transaction_type' => 0, 'amount' => $commission, 'balance' => $vendor->balance,'activity'=> 'Debit','narration'=>'Transfer To -'.$this->GetAepsUserNamesForAdmin($dipl_vendor->user_id)]);
    $walletAction = WalletAction::create([
      'user_id' => $request->user_id,
      'counterpart_id' => $dipl_vendor->user_id,
      'amount' => $commission,
      'remarks' => '',
      'status' => 1,
      'debit_id' => $commissionDebitTx->id,
      'type' => 0,
      'admin' => true,
      'automatic' => false,
      'commission' => true,
      'admin_id' => $admin_id
    ]);   

    // Credit DIPL Vendor Wallet with commission
    $dipl_vendor = DmtVendor::where('type', 8)->lockForUpdate()->first(); //@TODO: provide for multiple dipl_vendors
    $dipl_vendor->balance += $commission;
    $dipl_vendor->save();
    $dipl_commissionCreditTx = WalletTransaction::create(['user_id' => $dipl_vendor->user_id, 'transaction_type' => 1, 'amount' => $commission, 'balance' => $dipl_vendor->balance, 'activity' => 'Credit','narration'=>'Received From -'.$this->GetAepsUserNamesForAdmin($request->user_id)]);

    $walletAction = WalletAction::create([
      'user_id' => $dipl_vendor->user_id,
      'counterpart_id' => $vendor->user_id,
      'amount' => $commission,
      'remarks' => '',
      'status' => 1,
      'credit_id' => $dipl_commissionCreditTx->id,
      'type' => 0,
      'admin' => true,
      'automatic' => false,
      'commission' => true,
      'admin_id' => $admin_id
    ]);

    $request->status = 1;
    $request->admin_reference = Input::get('admin_ref_number');
    $request->save();

    if ($vendor->type != 1) return Response::json('Success', 200);

    // Debit DIPL Vendor Wallet by Distributor commission
    $dist_commission = $request->amount*(($vendor->user_id == 3971 ? 0.35 : 0.2)/100);

    $dipl_vendor = DmtVendor::where('type', 8)->lockForUpdate()->first(); //@TODO: provide for multiple dipl_vendors
    $dipl_vendor->balance -= $dist_commission;
    $dipl_vendor->save();
    $dipl_distCommissionDebitTx = WalletTransaction::create(['user_id' => $dipl_vendor->user_id, 'transaction_type' => 0, 'amount' => $dist_commission, 'balance' => $dipl_vendor->balance, 'activity' => 'Debit','narration'=>'Received From -'.$this->GetAepsUserNamesForAdmin($dipl_vendor->user_id)]);

    $walletAction = WalletAction::create([
      'user_id' => $dipl_vendor->user_id,
      'counterpart_id' => $request->user_id,
      'amount' => $dist_commission,
      'remarks' => '',
      'status' => 1,
      'debit_id' => $dipl_distCommissionDebitTx->id,
      'type' => 0,
      'admin' => true,
      'automatic' => false,
      'commission' => true,
      'admin_id' => $admin_id
    ]);

    // Credit Distributor Wallet by Distributor Commission
    // dd($vendor->parent_id);
    $dist = DmtVendor::where('user_id', $vendor->parent_id)->lockForUpdate()->first();

    $dist->balance += $dist_commission;
    $dist->save();

    // update wallet_transactions
    $distCommissionCreditTx = WalletTransaction::create(['user_id' => $dist->user_id, 'transaction_type' => 1, 'amount' => $dist_commission, 'balance' => $dist->balance, 'activity' => 'Credit','narration'=>'Transfer To -'.$this->GetAepsUserNamesForAdmin($dist->user_id)]);
    $walletAction = WalletAction::create([
      'user_id' => $dist->user_id,
      'counterpart_id' => $dipl_vendor->user_id,
      'amount' => $dist_commission,
      'remarks' => '',
      'status' => 1,
      'credit_id' => $distCommissionCreditTx->id,
      'type' => 0,
      'admin' => true,
      'automatic' => false,
      'commission' => true,
      'admin_id' => $admin_id
    ]);
  
    return Response::json('Success', 200); 
  }

  public function postRejectBalanceRequestByAdmin ($request_id)
  {
    $request = BalanceRequest::where('id', $request_id)->update(['status' => 2,'admin_reference' => Input::get('admin_ref_number')]);
    return Response::json('', 200);
  }


     //Added on 11/8/2017
  // public function getWalletReport ()
  //   {
  //       $wallets=0;
  //       $walletsObj=0;
  //       $dmt_vendor=DmtVendor::where('user_id', Auth::user()->id)->first();
  //       $user = Auth::user();
  //       if($dmt_vendor->type==1 && Auth::user()->type==4  &&  in_array('dmt', $user->permissions)){
  //       Paginator::setPageName('page');
  //       $walletsObj=DmtWalletAction::where('user_id', Auth::user()->id)->with('debit')->with('credit')->orderBy('id', 'DESC')->paginate(10);
  //       $wallets = $walletsObj->getItems();
  //       $wallets = array_map(function ($w)
  //       {
  //         $w->transaction = $w->debit ? $w->debit : ($w->credit ? $w->credit : null);
  //         $w->referenceNumber=DmtTransaction::where('id',$w->transaction_id)->first();
  //         $w->remitter_name=(isset($w->referenceNumber))?Remitter::where('id',$w->referenceNumber->remitter_id)->pluck('name'):'';
  //         $w->beneficiary_name=(isset($w->referenceNumber))?RemitterBeneficiary::where('id',$w->referenceNumber->beneficiary_id)->pluck('name'):'';
  //         return $w;      
  //       }, $wallets);
  //       }
  //       elseif($dmt_vendor->type==2 && Auth::user()->type==4 && in_array('dmt', $user->permissions)){
  //         Paginator::setPageName('page');
  //         $walletsObj=DmtWalletAction::where('user_id', Auth::user()->id)->with('debit')->with('credit')->orderBy('id', 'DESC')->paginate(10);
  //         $wallets = $walletsObj->getItems();
  //         $wallets = array_map(function ($w)
  //         {
  //           $w->transaction = $w->debit ? $w->debit : ($w->credit ? $w->credit : null);
  //           $w->referenceNumber=DmtTransaction::where('id',$w->transaction_id)->first();
  //           return $w;
  //         }, $wallets);
  //     }
  //     return View::make('reports.wallet-reports', ['wallets' => $wallets,'walletsObj' => $walletsObj,'dmt_vendor' => $dmt_vendor]);
  //   }

  //   public function getWalletExport() {
  //         $dmt_vendor=DmtVendor::where('user_id', Auth::user()->id)->first();
  //          $user = Auth::user();
  //          if($dmt_vendor->type==1 && Auth::user()->type==4 && in_array('dmt', $user->permissions)){
  //               if ((Input::get('from_date') && Input::get('to_date'))) {
  //                 $start_date = date('Y-m-d'. ' 00:00:00', strtotime(Input::get('from_date')));
  //                 $end_date = date('Y-m-d'. ' 23:59:59', strtotime(Input::get('to_date')));   
  //                 $records=DmtWalletAction::whereBetween('dmt_wallet_actions.created_at', [$start_date, $end_date])
  //                 ->where('dmt_wallet_actions.user_id',Auth::user()->id)
  //                 ->leftJoin('dmt_transactions','dmt_transactions.id','=','dmt_wallet_actions.transaction_id')->select(DB::raw("(Select LEFT(dmt_transactions.reference_number,10))as BC_Agent"),'dmt_transactions.reference_number as ReferenceNumber','dmt_wallet_actions.created_at as TransactionDate',
  //                   DB::raw("(if(dmt_wallet_actions.debit_id !=0 and dmt_wallet_actions.credit_id=0,CONCAT('Transferred to -',(select name from remitter_beneficiaries where id=dmt_transactions.beneficiary_id 
  //                   )),CONCAT('Send from -',(select name from remitters where id=dmt_transactions.remitter_id )))) as Activity"),
  //                   DB::raw("
  //                   (CASE WHEN dmt_wallet_actions.debit_id !=0 and dmt_wallet_actions.credit_id=0 THEN (select (wallet_transactions.balance + wallet_transactions.amount) from wallet_transactions where wallet_transactions.id=dmt_wallet_actions.credit_id 
  //                   )
  //                   WHEN dmt_wallet_actions.debit_id =0 and dmt_wallet_actions.credit_id !=0 THEN (select (wallet_transactions.balance - wallet_transactions.amount) from wallet_transactions where wallet_transactions.id=dmt_wallet_actions.debit_id
  //                   )END)
  //                    as Opening_Balance"),
  //                    DB::raw("
  //                   (CASE WHEN dmt_wallet_actions.credit_id !=0 and dmt_wallet_actions.debit_id=0 THEN (SELECT amount FROM wallet_transactions WHERE wallet_transactions.id=dmt_wallet_actions.credit_id 
  //                   )         
  //                   END)
  //                    as Credit"),
  //                     DB::raw("
  //                   (CASE WHEN dmt_wallet_actions.debit_id !=0 and dmt_wallet_actions.credit_id=0 THEN (SELECT amount FROM wallet_transactions WHERE wallet_transactions.id=dmt_wallet_actions.debit_id 
  //                   )
  //                   END)
  //                    as Debit"),
  //                   DB::raw("(if(dmt_wallet_actions.debit_id !=0 and dmt_wallet_actions.credit_id=0,CONCAT('',(SELECT balance FROM wallet_transactions WHERE wallet_transactions.id=dmt_wallet_actions.debit_id)),CONCAT('',
  //                   (SELECT balance FROM wallet_transactions WHERE wallet_transactions.id=dmt_wallet_actions.credit_id)))) as Closing_Balance"))
  //                   ->orderBy('dmt_wallet_actions.id', 'DESC')->get();
  //                   }    
  //             }
  //             elseif($dmt_vendor->type==2 && Auth::user()->type==4 && in_array('dmt', $user->permissions)){
  //               if ((Input::get('from_date') && Input::get('to_date'))) {
  //                 $start_date = date('Y-m-d'. ' 00:00:00', strtotime(Input::get('from_date')));
  //                 $end_date = date('Y-m-d'. ' 23:59:59', strtotime(Input::get('to_date')));
  //                  $records =DmtWalletAction::whereBetween('dmt_wallet_actions.created_at', [$start_date, $end_date])
  //                  ->where('dmt_wallet_actions.user_id',Auth::user()->id)
  //                  ->leftJoin('dmt_transactions','dmt_transactions.id','=','dmt_wallet_actions.transaction_id')
  //                  ->select('dmt_transactions.reference_number as ReferenceNumber','dmt_wallet_actions.created_at as TransactionDate', 
  //                    DB::raw("
  //                   (CASE WHEN dmt_wallet_actions.credit_id !=0 and dmt_wallet_actions.debit_id=0 THEN (SELECT amount FROM wallet_transactions WHERE wallet_transactions.id=dmt_wallet_actions.credit_id 
  //                   )         
  //                   END)
  //                    as Credit"),
  //                    DB::raw("
  //                   (CASE WHEN dmt_wallet_actions.debit_id !=0 and dmt_wallet_actions.credit_id=0 THEN (SELECT amount FROM wallet_transactions WHERE wallet_transactions.id=dmt_wallet_actions.debit_id 
  //                   )
  //                   END)
  //                    as Debit"),
  //                   DB::raw("(if(dmt_wallet_actions.debit_id !=0 and dmt_wallet_actions.credit_id=0,CONCAT('',(SELECT balance FROM wallet_transactions WHERE wallet_transactions.id=dmt_wallet_actions.debit_id)),CONCAT('',
  //                   (SELECT balance FROM wallet_transactions WHERE wallet_transactions.id=dmt_wallet_actions.credit_id)))) as Closing_Balance"),'dmt_wallet_actions.remarks as Remarks')
  //                   ->orderBy('dmt_wallet_actions.id', 'DESC')
  //                   ->get()
  //                   ->toArray();
  //                   }    
  //             } 
  //             dd($records);   
  //       $export_csv= new Export();
  //       $export_csv->exportData($records,"wallets-report-");
  //   }


    public function getWalletReport ()
    {
      $current_page=Input::get('page') ? Input::get('page') : "1";
    /*Web services*/
     $headers = [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json'
        ];

         $data = [
          'reporttype' =>"WT",
          'user_id' =>(string)\Cookie::get('userid'),
          "currentpage"=>$current_page,
          "per_page"=>"100",
            "from_date"=>"",
          "to_date"=>"",
          "is_export"=>"false"
           ];


             
         
        $body = Unirest\Request\Body::json($data);

      $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/RequesttransactionreportAgentdist', $headers, $body);
      // $errorrem= new ErrorRmitter();
      // $errorrem->request=json_encode($data);
      // $errorrem->response=json_encode($response);
      // $errorrem->save();
      //dd($response);
    /*ENd web services*/

//dd($response->body-response);
            $record=1;
               $per_page=0;
               $total=0;
               $current_page=0;
          if(isset($response->body->requestvalues)){

            if(isset($response->raw_body)){
            
            

          $response_data=json_decode($response->raw_body);
          $per_page=$response_data->per_page;
          $total=$response_data->total;
          $current_page=$response_data->current_page;

          foreach ($response_data->requestvalues as $requests) {

            $request_data[] = json_decode($requests);
          }
     

            }else{
              $request_data='';
               $record=0;
            }
        }else{
          $request_data[]='';
          $record=0;
        }




      return View::make('reports.wallet-reports',['wallets'=>$request_data ,'status' =>$record,'per_page'=>$per_page,'total'=>$total,'current_page'=>$current_page,'code'=>$record]);
      
    }





    public function getWalletReportdaywise ()
    {
      $current_page=Input::get('page') ? Input::get('page') : "1";
    /*Web services*/
     $headers = [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json'
        ];

         $data = [
          'reporttype' =>"WT",
          'user_id' =>(string)\Cookie::get('userid'),
          "currentpage"=>$current_page,
          "per_page"=>"100",
           "from_date"=>Input::get('from_date'),
          "to_date"=>Input::get('to_date'),
          "is_export"=>"false"
           ];


             
         
        $body = Unirest\Request\Body::json($data);

      $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/RequesttransactionreportAgentdist', $headers, $body);
      // $errorrem= new ErrorRmitter();
      // $errorrem->request=json_encode($data);
      // $errorrem->response=json_encode($response);
      // $errorrem->save();
      //dd($response);
    /*ENd web services*/

//dd($response->body-response);
            $record=1;
               $per_page=0;
               $total=0;
               $current_page=0;
          if(isset($response->body->requestvalues)){

            if(isset($response->raw_body)){
            
            

          $response_data=json_decode($response->raw_body);
          $per_page=$response_data->per_page;
          $total=$response_data->total;
          $current_page=$response_data->current_page;

          foreach ($response_data->requestvalues as $requests) {

            $request_data[] = json_decode($requests);
          }
     

            }else{
              $request_data='';
               $record=0;
            }
        }else{
          $request_data[]='';
          $record=0;
        }




      return View::make('reports.wallet-reports',['wallets'=>$request_data ,'status' =>$record,'per_page'=>$per_page,'total'=>$total,'current_page'=>$current_page,'code'=>$record]);
      
    }


    public function getWalletReportdaywiseexport ()
    {
      $current_page=Input::get('page') ? Input::get('page') : "1";
    /*Web services*/
     $headers = [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json'
        ];

         $data = [
          'reporttype' =>"WT",
          'user_id' =>(string)\Cookie::get('userid'),
          "currentpage"=>$current_page,
          "per_page"=>"100",
           "from_date"=>Input::get('from_date'),
          "to_date"=>Input::get('to_date'),
          "is_export"=>"true"
           ];


             
         
        $body = Unirest\Request\Body::json($data);

      $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/RequesttransactionreportAgentdist', $headers, $body);
      // $errorrem= new ErrorRmitter();
      // $errorrem->request=json_encode($data);
      // $errorrem->response=json_encode($response);
      // $errorrem->save();
      //dd($response);
    /*ENd web services*/

//dd($response->body-response);
            $record=1;
               $per_page=0;
               $total=0;
               $current_page=0;
          if(isset($response->body->requestvalues)){

            if(isset($response->raw_body)){
            
            

          $response_data=json_decode($response->raw_body);
          $per_page=$response_data->per_page;
          $total=$response_data->total;
          $current_page=$response_data->current_page;

          foreach ($response_data->requestvalues as $requests) {

            $request_data[] = json_decode($requests);
          }
     

            }else{
              $request_data='';
               $record=0;
            }
        }else{
          $request_data[]='';
          $record=0;
        }



  $export_csv= new Export();
      $export_csv->exportData(json_decode( json_encode($request_data), true),"wallet-report-");
      // return View::make('reports.wallet-reports',['wallets'=>$request_data ,'status' =>$record,'per_page'=>$per_page,'total'=>$total,'current_page'=>$current_page,'code'=>$record]);
      
    }








    public function getWalletExport() {

          $dmt_vendor_data = new DmtVendor;
          $dmt_vendor_data->setConnection('mysql2');

          $wallet_transaction_data = new WalletTransaction;
          $wallet_transaction_data->setConnection('mysql2');

          $dmt_vendor=$dmt_vendor_data->where('user_id', Auth::user()->id)->first();
           $user = Auth::user();
           if($dmt_vendor->type==1 && Auth::user()->type==4 && in_array('dmt', $user->permissions)){
                if ((Input::get('from_date') && Input::get('to_date'))) {
                  $start_date = date('Y-m-d'. ' 00:00:00', strtotime(Input::get('from_date')));
                  $end_date = date('Y-m-d'. ' 23:59:59', strtotime(Input::get('to_date')));   
                  $records=$wallet_transaction_data->whereBetween('wallet_transactions.created_at', [$start_date, $end_date])
                  ->where('wallet_transactions.user_id',Auth::user()->id)
                  ->select('wallet_transactions.created_at as TransactionDate',
                     'wallet_transactions.activity as Activity','wallet_transactions.narration',DB::raw("(CASE wallet_transactions.transaction_type
                      WHEN '1' THEN (wallet_transactions.balance - wallet_transactions.amount)
                      WHEN '0' THEN (wallet_transactions.balance + wallet_transactions.amount)
                      END) as Opening_Balance"),
                     DB::raw("(CASE wallet_transactions.transaction_type
                      WHEN '1' THEN wallet_transactions.amount
                      WHEN '0' THEN '0'
                      END) as Credit"),
                   DB::raw("(CASE wallet_transactions.transaction_type
                      WHEN '0' THEN wallet_transactions.amount
                      WHEN '1' THEN '0'
                      END) as Debit"),
                 DB::raw("(CASE wallet_transactions.transaction_type
                      WHEN '1' THEN wallet_transactions.balance
                      WHEN '0' THEN wallet_transactions.balance 
                      END) as Closing_Balance"))
                    ->orderBy('wallet_transactions.id', 'DESC')->get();
                    }    
              }
              elseif($dmt_vendor->type==2 && Auth::user()->type==4 && in_array('dmt', $user->permissions)){
                if ((Input::get('from_date') && Input::get('to_date'))) {
                  $start_date = date('Y-m-d'. ' 00:00:00', strtotime(Input::get('from_date')));
                  $end_date = date('Y-m-d'. ' 23:59:59', strtotime(Input::get('to_date')));
                   $records=$wallet_transaction_data->whereBetween('wallet_transactions.created_at', [$start_date, $end_date])
                  ->where('wallet_transactions.user_id',Auth::user()->id)
                  ->select('wallet_transactions.created_at as TransactionDate',
                     'wallet_transactions.activity as Activity','wallet_transactions.narration',DB::raw("(CASE wallet_transactions.transaction_type
                      WHEN '1' THEN (wallet_transactions.balance - wallet_transactions.amount)
                      WHEN '0' THEN (wallet_transactions.balance + wallet_transactions.amount)
                      END) as Opening_Balance"),
                     DB::raw("(CASE wallet_transactions.transaction_type
                      WHEN '1' THEN wallet_transactions.amount
                      WHEN '0' THEN '0'
                      END) as Credit"),
                   DB::raw("(CASE wallet_transactions.transaction_type
                      WHEN '0' THEN wallet_transactions.amount
                      WHEN '1' THEN '0'
                      END) as Debit"),
                 DB::raw("(CASE wallet_transactions.transaction_type
                      WHEN '1' THEN wallet_transactions.balance
                      WHEN '0' THEN wallet_transactions.balance 
                      END) as Closing_Balance"))
                    ->orderBy('wallet_transactions.id', 'DESC')->get();
                    }    
              } 
              //dd($records);   
        $export_csv= new Export();
        $export_csv->exportData($records,"wallets-report-");
    }



    public function getDistributorWalletReport ()
    {
        $wallets=0;
        $walletsObj=0;

        $dmt_vendor_data = new DmtVendor;
        $dmt_vendor_data->setConnection('mysql2');
        $wallet_transaction_data = new WalletTransaction;
        $wallet_transaction_data->setConnection('mysql2');

        $dmt_vendor=$dmt_vendor_data->where('user_id', Auth::user()->id)->first();
        $user = Auth::user();
        if($dmt_vendor->type==2 && Auth::user()->type==4 && in_array('dmt', $user->permissions)){
          Paginator::setPageName('page');
          $walletsObj=$wallet_transaction_data->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->paginate(100);
          $wallets = $walletsObj->getItems();
          //dd($wallets);
      }
      return View::make('reports.wallet-reports-distributor', ['wallets' => $wallets,'walletsObj' => $walletsObj]);
    }

    public function getDistributorWalletExport() {

      $dmt_vendor_data = new DmtVendor;
      $dmt_vendor_data->setConnection('mysql2');
      $dmt_vendor=$dmt_vendor_data->where('user_id', Auth::user()->id)->first();
      $wallet_transaction_data = new WalletTransaction;
      $wallet_transaction_data->setConnection('mysql2');
      $user = Auth::user();
       if($dmt_vendor->type==2 && Auth::user()->type==4 && in_array('dmt', $user->permissions)){
                if ((Input::get('from_date') && Input::get('to_date'))) {
                  $start_date = date('Y-m-d'. ' 00:00:00', strtotime(Input::get('from_date')));
                  $end_date = date('Y-m-d'. ' 23:59:59', strtotime(Input::get('to_date')));
            $records =$wallet_transaction_data->whereBetween('wallet_transactions.created_at', [$start_date, $end_date])
              ->where('wallet_transactions.user_id', Auth::user()->id)
              ->select('wallet_transactions.id as Id',
                DB::raw("(CASE wallet_transactions.transaction_type
                WHEN '1' THEN wallet_transactions.amount
                WHEN '0' THEN '0'
                END) as Credit"),
            DB::raw("(CASE wallet_transactions.transaction_type
                WHEN '0' THEN wallet_transactions.amount
                WHEN '1' THEN '0'
                END) as Debit")
,'wallet_transactions.balance as Balance','wallet_transactions.created_at as Date')
                    ->orderBy('wallet_transactions.id', 'DESC')
                    ->get()
                    ->toArray();
                    }    
              }    
        $export_csv= new Export();
        $export_csv->exportData($records,"distributor-wallets-report-");
    }

}

