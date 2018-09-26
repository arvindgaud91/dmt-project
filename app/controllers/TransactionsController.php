<?php
use Acme\Auth\Auth;
use Acme\Helper\Rabbit;
use Acme\Helper\GateKeeper;
use Carbon\Carbon;
use Acme\Helper\Export;

class TransactionsController extends \BaseController
{
  public function getTransactionForm()
  {
    $data['transactionType'] = Input::get('type');
    $data['banks'] = Bank::get();
    return View::make('transactions.transaction')->with($data);
  }

  public function getImpsForm ()
  {
    //dd($ben_id);
    //$datas=explode("-",$ben_id);
    $data['remitter_id']=Input::get('remitterid');//$datas[0];
    $data['beneficiary']=Input::get('beneficiaryid');//$datas[1];
    $data['beneficiaryname']=Input::get('beneficiaryname');//$datas[2];
    $data['accountnumber']=Input::get('accountnumber');//$datas[3];
    $data['ifscode']=Input::get('ifscode');//$datas[4];
    $data['consumed_limit']=Input::get('consumedlimit');//$datas[5];

    if (! $data) return Redirect('/');
    return View::make('sender.imps')->with($data);
  }

  public function getNeftForm ()
  {
    //dd($ben_id);
    $datas=Input::all();//explode("-",$ben_id);
    if(isset($datas))
    {

    $data['remitter_id']=Input::get('remitterid');//$datas[0];
    $data['beneficiary']=Input::get('beneficiaryid');//$datas[1];
    $data['beneficiaryname']=Input::get('beneficiaryname');//$datas[2];
    $data['accountnumber']=Input::get('accountnumber');//$datas[3];
    $data['ifscode']=Input::get('ifscode');//$datas[4];
    $data['consumed_limit']=Input::get('consumedlimit');//$datas[5];
    
    if (! $data) return Redirect('/');
    return View::make('sender.neft')->with($data);
  }else
  {$data='';
 return View::make('sender.neft')->with($data);
  }

   
  }

  public function getpaytmForm ($ben_id)
  {
    $data['beneficiary'] = RemitterBeneficiary::with('dmtBankBranch')->find($ben_id);
    if (! $data['beneficiary']) return Redirect('/');
    return View::make('sender.paytm')->with($data);
  }

  
  public function getpaytmImpsForm ($ben_id)
  {
    $data['beneficiary'] = RemitterBeneficiary::with('dmtBankBranch')->find($ben_id);
    
     $data['remitter_mobile']= Remitter::where('id', $data['beneficiary']->remitter_id)->pluck('phone_no');
    
    if (! $data['beneficiary']) return Redirect('/');
    return View::make('sender.paytm-imps')->with($data);
  }
  public function postNeft ()
  {
    if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
    $user = Auth::user();
  
//dd($user);
    //@TODO: Extra Validations, if any
    $validator = Validator::make(Input::all(), [
      'amount' => 'required',
      'remitter_id' => 'required|integer',
      'beneficiary_id' => 'required|integer'
    ]);
    if ($validator->fails())
      return Response::json($validator->messages(), 422);

   $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
      ];

//$userid=\Cookie::get('userid');
    $data = [
        'userid'=>(string)\Cookie::get('userid'),
        'sessiontoken'=>(string)\Cookie::get('user'),
        'bcagent'=>(string)\Cookie::get('bcagent'),
        'remitterid' =>Input::get('remitter_id'),
        'beneficiaryid' =>Input::get('beneficiary_id'),
        'amount'=>Input::get('amount'),
        'remarks'=>'transaction',
        'channelpartnerrefno'=>(string)\Cookie::get('bcagent').time(),
        'flag'=>'3','process_type'=>'1','req_type'=>'W'
    ];
     
    $body = Unirest\Request\Body::json($data);
    $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/TransactionRequest', $headers, $body);

    $uniqueid = mt_rand(100000, 999999);
    // log something to storage/logs/debug.log
    Log::useFiles(storage_path().'/logs/neft-debug.log');
    Log::info(['RequestID'=>$uniqueid] );
    Log::info("\n");
    Log::info(['Request'=>$body]);
    Log::info("\n");
    Log::info(['Response'=>$response->body]);
    Log::info("\n");
    Log::info("------------------------------------" );

    // $errorrem= new ErrorRmitter();
    // $errorrem->request=json_encode($data);
    // $errorrem->response=json_encode($response);
    // $errorrem->save();

  if($response->body->status==1)
  {
    return Response::json(['message' =>  'success' , 'transaction_group_id' => $response->body->transaction_Group_id ,'status'=>1 ], 200);
  }else
  {
    if(empty($response->body->transaction_Group_id))
    {



       if(isset($response->body->description)) 
      {
       $des=$response->body->description;
      }else
      {
     $des='';
      }
    return Response::json(['message' => $des , 'transaction_group_id' => '' ,'status'=>0], 200);


    }else
    {
     return Response::json(['message' =>  'error' , 'transaction_group_id' => $response->body->transaction_Group_id,'status'=>1], 200);

  }


}


  

  }

  public function postImps ()
  {
    if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
    $user = Auth::user();
   
 
    //@TODO: Extra Validations, if any
    $validator = Validator::make(Input::all(), [
      'amount' => 'required',
      'remitter_id' => 'required|integer',
      'beneficiary_id' => 'required|integer'
    ]);
    if ($validator->fails())
      return Response::json($validator->messages(), 422);
    $transactionSubmitted = $this->filterOnly(Input::all(), ['amount', 'remitter_id', 'beneficiary_id']);
    if ($transactionSubmitted['amount'] > 49999) return Response::json(['message' => 'Transaction limit exceeded.'], 422);
    

    $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
      ];

//$userid=\Cookie::get('userid');
       $data = [
        'userid'=>(string)\Cookie::get('userid'),
       'sessiontoken'=>(string)\Cookie::get('user'),
        'bcagent'=>(string)\Cookie::get('bcagent'),
        'remitterid' =>Input::get('remitter_id'),
        'beneficiaryid' =>Input::get('beneficiary_id'),
        'amount'=>Input::get('amount'),
        'remarks'=>'transaction',
        'channelpartnerrefno'=>(string)\Cookie::get('bcagent').time(),
        'flag'=>'2','process_type'=>'1','req_type'=>'W'
         ];

      
      $body = Unirest\Request\Body::json($data);

    $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/TransactionRequest', $headers, $body);

    $uniqueid = mt_rand(100000, 999999);
    // log something to storage/logs/debug.log
    Log::useFiles(storage_path().'/logs/imps-debug.log');
    Log::info(['RequestID'=>$uniqueid]);
    Log::info("\n");
    Log::info(['Request'=>$body]);
    Log::info("\n");
    Log::info(['Response'=>$response->body]);
    Log::info("------------------------------------" );
      // $errorrem= new ErrorRmitter();
      // $errorrem->request=json_encode($data);
      // $errorrem->response=json_encode($response);
      // $errorrem->save();

  if($response->body->status==1)
  {

return Response::json(['message' =>  'success' , 'transaction_group_id' => $response->body->transaction_Group_id,'status'=>1], 200);

}else
{
  if(empty($response->body->transaction_Group_id))
    {



       if(isset($response->body->description)) 
      {
       $des=$response->body->description;
      }else
      {
     $des='';
      }
    return Response::json(['message' => $des , 'transaction_group_id' => '' ,'status'=>0], 200);


    }else
    {
     return Response::json(['message' =>  'error' , 'transaction_group_id' => $response->body->transaction_Group_id,'status'=>1], 200);

  }
}
    




   // return Response::json(['message' =>  $message , 'transaction_group_id' => $transaction->transaction_group_id], $code);
  }

  private function walletUpdates ($dmt_vendor_id, $transaction)
  {
      $dmt_vendor = DmtVendor::lockForUpdate()->find($dmt_vendor_id);
      $dmt_vendor->balance -= $transaction->amount;
      $dmt_vendor->save();

      $remitter=Remitter::where('id','=',$transaction->remitter_id)->first();
      //dd($remitter);
      $remitter_phone=$remitter->phone_no;
      

      $debit_tx = WalletTransaction::create([
          'user_id' => $dmt_vendor->user_id,
          'transaction_type' => 0,
          'activity' =>'Transfer:-'.$transaction->reference_number.'/'.$remitter_phone,
          'amount' => $transaction->amount,
          'balance' => $dmt_vendor->balance
        ]);

      DmtWalletAction::create([
          'user_id' => $dmt_vendor->user_id,
          'amount' => $transaction->amount,
          'debit_id' => $debit_tx->id,
          'status' => 1,
          'transaction_id' => $transaction->id,
          'transaction_type' => $transaction->type
        ]);
      
      return true;

  }

  private function reverseWalletUpdates ($dmt_vendor_id, $transaction)
  {
      $dmt_vendor = DmtVendor::lockForUpdate()->find($dmt_vendor_id);
      $dmt_vendor->balance += $transaction->amount;
      $dmt_vendor->save();

      $remitter=Remitter::where('id','=',$transaction->remitter_id)->first();
      //dd($remitter);
      $remitter_phone=$remitter->phone_no;
      

      $credit_tx = WalletTransaction::create([
          'user_id' => $dmt_vendor->user_id,
          'transaction_type' => 1,
          'activity' =>$transaction->reference_number.'/'.$remitter_phone,
          'amount' => $transaction->amount,
          'balance' => $dmt_vendor->balance
        ]);

      DmtWalletAction::create([
          'user_id' => $dmt_vendor->user_id,
          'amount' => $transaction->amount,
          'credit_id' => $credit_tx->id,
          'status' => 1,
          'transaction_id' => $transaction->id,
          'transaction_type' => $transaction->type
        ]);
      
      return true;

  }

  public function getTransactionReceipt ($id)
  {
    $transaction = AepsTransaction::where('id', $id)->with('user.vendorDetails')->first();
    $distId = User::find($transaction->user_id)->vendorDetails->parent_id;
    $superDistId = User::find($distId)->vendorDetails->parent_id;
    if ($transaction->user_id != Auth::user()->id && $distId != Auth::user()->id && $superDistId != Auth::user()->id)
      return Redirect::to('/');
    $aadhaar_code = AadhaarCode::where('response_code', $transaction->result_code)->first();
    $transaction->result_message = $aadhaar_code ? $aadhaar_code->description : '';
    $data['transaction'] = $transaction;
    return View::make('receipts.receipt')->with($data);
  }

  public function transactt ()
  {
   if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
    $user = Auth::user();


 $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
      ];

//$userid=\Cookie::get('userid');
       $data = [
        'userid'=>(string)\Cookie::get('userid'),
       'sessiontoken'=>(string)\Cookie::get('user'),
        'bcagent'=>(string)\Cookie::get('bcagent'),
        'remitterid' =>'756565',
        'beneficiaryid' =>'1244978',
        'amount'=>'100',
        'remarks'=>'transaction',
        'channelpartnerrefno'=>(string)\Cookie::get('bcagent').time(),
        'flag'=>'2','process_type'=>'1','req_type'=>'W'
         ];

      
      $body = Unirest\Request\Body::json($data);

    $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/TransactionRequest', $headers, $body);


      // $errorrem= new ErrorRmitter();
      // $errorrem->request=json_encode($data);
      // $errorrem->response=json_encode($response);
      // $errorrem->save();

    dd($response);




    $headers = [
      'Accept' => 'text/xml',
      'Content-Type' => 'text/xml'
    ];
    $payload = '<transactionreq>
      <header>
      <sessiontoken>'.$user->dmt_vendor->session_token.'</sessiontoken>
      </header>
      <bcagent>'.$user->dmt_vendor->bc_agent.'</bcagent>
      <remitterid>'.$remitter->rbl_remitter_code.'</remitterid>
      <beneficiaryid>'.$beneficiary->rbl_beneficiary_code.'</beneficiaryid>
      <amount>'.$transaction->amount.'</amount>
      <remarks>transaction</remarks>
      <cpid>46</cpid>
      <channelpartnerrefno>'.$transaction->reference_number.'</channelpartnerrefno>
      <flag>'.$flag.'</flag>
    </transactionreq>';

    $input = Parser::xml($payload);
    $dmt_transaction_log = DmtTransactionLog::create(['transaction_id' => $transaction->id, 'request' => json_encode($input)]);
    Log::info("Login request: ".json_encode($input));

    Unirest\Request::verifyPeer(false);
    $response = Unirest\Request::post(getenv('RBL_DMT_URL'), $headers, $payload);
    $output = Parser::xml(mb_convert_encoding($response->body, 'UTF-16', 'UTF-8'));
    
    //dd($output);
    if($output['status']==1)
    {

       $rbl_tran_id=$output['RBLtransactionid'];
        $remarks=$output['remarks'];
        $bankrefno=$output['bankrefno'];

        if($transaction->type==2)//imps 
        {
        $NPCIResponsecode=$output['NPCIResponsecode'] || '';
        }else
        {
        $NPCIResponsecode='';
        }
        
        if($transaction->type==1) //neft
        {
        $utr_code=$output['UTRNo'] || '';
        }else
        {
        $utr_code='';
        }
     }
     else
     {
       if(isset($output['description']))
       {  
         $rbl_tran_id='';
      $remarks='';
      $bankrefno='';
      $NPCIResponsecode='';
      $utr_code='';
 
       }else
       {
        $rbl_tran_id=$output['RBLtransactionid'];
        $remarks=$output['remarks'];
        $bankrefno=$output['bankrefno'];
        $NPCIResponsecode='';
        $utr_code='';
       }
     

     }

    $transaction->bank_transaction_id=$rbl_tran_id;
    $transaction->remarks=$remarks;
    $transaction->utr_code=$utr_code;
    $transaction->bank_reference_number=$bankrefno;
    $transaction->npci_result_code=$NPCIResponsecode;
    $transaction->save();
    
    $dmt_transaction_log->response = json_encode($output);
    $dmt_transaction_log->save();

    if ($response->code >= 400) {
      Log::info($response->code.' '.json_encode($response->body));
      return false;
    }
    
    return $output;


  }

  private function limitTransactions ($aadhar_no, $transaction_type) {
    return AepsTransaction::where('aadhar_no', $aadhar_no)
      ->where('type', $transaction_type)
      ->where('status', '>', 0)
      ->whereDate('created_at', '=', Carbon::today()->toDateString())
      ->count() < 5 ? true : false;
  }

  public function getTransactionReport()
  {

 
$current_page=Input::get('page') ? Input::get('page') : "1";
    /*Web services*/
     $headers = [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json'
        ];

         $data = [
          'reporttype' =>"TR",
          'user_id' =>(string)\Cookie::get('userid'),
          "currentpage"=>$current_page,
          "per_page"=>"100",
          "from_date"=>"",
          "to_date"=>"",
          "is_export"=>"false"
           ];


             
         
        $body = Unirest\Request\Body::json($data);

      //$response = Unirest\Request::post(getenv('WS_URL').'/DMTService/transactionrequest', $headers, $body);
      $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/RequesttransactionreportAgentdist', $headers, $body);
        //dd($response);
      // $errorrem= new ErrorRmitter();
      // $errorrem->request=json_encode($data);
      // $errorrem->response=json_encode($response);
      // $errorrem->save();
     
    /*ENd web services*/


    if($response->code == 200)
    {         $record=1;
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



      return View::make('reports.transaction-reports',['transactions'=>$request_data,'per_page'=>$per_page,'total'=>$total,'current_page'=>$current_page,'record'=>$record,'from_date'=>0,'to_date'=>0,'url'=>'transaction-reports']);
      }


  }

  public function getTransactionReportpage($id,$idd,$iddd)
  {

 
$current_page=$id;
if($idd==0){$dd='';}else{$dd=$idd;}
if($iddd==0){$ddd='';}else{$ddd=$iddd;}
//dd($current_page);
    /*Web services*/
     $headers = [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json'
        ];

         $data = [
          'reporttype' =>"TR",
          'user_id' =>(string)\Cookie::get('userid'),
          "currentpage"=>$current_page,
          "per_page"=>"100",
          "from_date"=>$dd,
          "to_date"=>$ddd,
          "is_export"=>"false"
           ];


             
         
        $body = Unirest\Request\Body::json($data);

      //$response = Unirest\Request::post(getenv('WS_URL').'/DMTService/transactionrequest', $headers, $body);
      $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/RequesttransactionreportAgentdist', $headers, $body);
        //dd($response);
      // $errorrem= new ErrorRmitter();
      // $errorrem->request=json_encode($data);
      // $errorrem->response=json_encode($response);
      // $errorrem->save();
     
    /*ENd web services*/


    if($response->code == 200)
    {         $record=1;
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



      return View::make('reports.transaction-reports',['transactions'=>$request_data,'per_page'=>$per_page,'total'=>$total,'current_page'=>$current_page,'record'=>$record,'from_date'=>$idd,'to_date'=>$iddd,'url'=>'transaction-reportsdatewise']);
      }


  }

  public function getTransactionReportdatewise()
  {

 
$current_page=Input::get('page') ? Input::get('page') : "1";
    /*Web services*/
     $headers = [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json'
        ];

         $data = [
          'reporttype' =>"TR",
          'user_id' =>(string)\Cookie::get('userid'),
          "currentpage"=>$current_page,
          "per_page"=>"100",
          "from_date"=>Input::get('from_date'),
          "to_date"=>Input::get('to_date'),
          "is_export"=>"false"
           ];


             
         
        $body = Unirest\Request\Body::json($data);

      //$response = Unirest\Request::post(getenv('WS_URL').'/DMTService/transactionrequest', $headers, $body);
      $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/RequesttransactionreportAgentdist', $headers, $body);
      //   //dd($response);
      // $errorrem= new ErrorRmitter();
      // $errorrem->request=json_encode($data);
      // $errorrem->response=json_encode($response);
      // $errorrem->save();
     
    /*ENd web services*/


    if($response->code == 200)
    {         $record=1;
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



      return View::make('reports.transaction-reports',['transactions'=>$request_data,'per_page'=>$per_page,'total'=>$total,'current_page'=>$current_page,'record'=>$record,'from_date'=>Input::get('from_date'),'to_date'=>Input::get('to_date'),'url'=>'transaction-reportsdatewise']);
      }


  }


  public function getTransactionReportexport()
  {

 
$current_page=Input::get('page') ? Input::get('page') : "1";
    /*Web services*/
     $headers = [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json'
        ];

         $data = [
          'reporttype' =>"TR",
          'user_id' =>(string)\Cookie::get('userid'),
          "currentpage"=>$current_page,
          "per_page"=>"100",
          "from_date"=>Input::get('from_date'),
          "to_date"=>Input::get('to_date'),
          "is_export"=>"true"
           ];


             
         
        $body = Unirest\Request\Body::json($data);

      //$response = Unirest\Request::post(getenv('WS_URL').'/DMTService/transactionrequest', $headers, $body);
      $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/RequesttransactionreportAgentdist', $headers, $body);
        //dd($response);
      // $errorrem= new ErrorRmitter();
      // $errorrem->request=json_encode($data);
      // $errorrem->response=json_encode($response);
      // $errorrem->save();
     
    /*ENd web services*/
//$datass = $response->body->requestvalues;

    if($response->code == 200)
    {         $record=1;
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
      $export_csv->exportData(json_decode( json_encode($request_data), true),"Transaction-report-");


     // return View::make('reports.transaction-reports',['transactions'=>$request_data,'per_page'=>$per_page,'total'=>$total,'current_page'=>$current_page,'record'=>$record]);
      }


  }


  public function getDMTTransactionReceipt($txId)
  {
   $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
      ];

      $data = [
         'tran_group_id'=>$txId         
      ];

    $body = Unirest\Request\Body::json($data);

    $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/Transactionprintrequest', $headers, $body);
//dd($response);
if($response->body->status == 1)
{
  return View::make('receipts.receipt', ['transactions' => $response->body]);

}else
{
return View::make('receipts.receipt', ['transactions' => '']);

}
  }

  public function getReceipt($txId)
  {
     
/*Web services*/
    $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
      ];

      $data = [
         'tran_group_id'=>$txId         
      ];

    $body = Unirest\Request\Body::json($data);

    $response = Unirest\Request::post(getenv('WS_URL').'/DMTService/Transactionprintrequest', $headers, $body);
//dd($response);
if($response->body->status == 1)
{
  return View::make('receipts.receipt', ['transactions' => $response->body]);

}else
{
return View::make('receipts.receipt', ['transactions' => '']);

}




  }

  public function getTransactionExport() {

        $dmt_vendor_data = new DmtVendor;
        $dmt_vendor_data->setConnection('mysql2');

        $dmt_transaction_data = new DmtTransaction;
        $dmt_transaction_data->setConnection('mysql2');

          $dmt_vendor=$dmt_vendor_data->where('user_id', Auth::user()->id)->first();
           $user = Auth::user();
           if($dmt_vendor->type==1 && Auth::user()->type==4 && in_array('dmt', $user->permissions)){
                if ((Input::get('from_date') && Input::get('to_date'))) {
                  $start_date = date('Y-m-d'. ' 00:00:00', strtotime(Input::get('from_date')));
                  $end_date = date('Y-m-d'. ' 23:59:59', strtotime(Input::get('to_date')));
                  $records = $dmt_transaction_data->whereBetween('dmt_transactions.created_at', [$start_date, $end_date])->where('dmt_transactions.user_id', Auth::user()->id)
                  ->join('remitters','dmt_transactions.remitter_id','=','remitters.id')
                  ->join('remitter_beneficiaries','dmt_transactions.beneficiary_id','=','remitter_beneficiaries.id')->orderBy('dmt_transactions.id', 'DESC')
                  ->select('dmt_transactions.bank_transaction_id as BankTransactionId','dmt_transactions.reference_number as ReferenceNumber','dmt_transactions.created_at as TransactionDate','remitters.name as SenderName','remitters.phone_no as MobileNo','remitter_beneficiaries.name as BeneficiaryName','remitter_beneficiaries.account_number as AccountNumber','dmt_transactions.amount as TotalAmount','dmt_transactions.status as Status','dmt_transactions.utr_code as UTR_Code','dmt_transactions.remarks as BankRemarks')->get()->toArray();
                    }    
              }
              elseif($dmt_vendor->type==2 && Auth::user()->type==4 && in_array('dmt', $user->permissions)){
                if ((Input::get('from_date') && Input::get('to_date'))) {
                  $start_date = date('Y-m-d'. ' 00:00:00', strtotime(Input::get('from_date')));
                  $end_date = date('Y-m-d'. ' 23:59:59', strtotime(Input::get('to_date')));
                  $records = $dmt_transaction_data->whereBetween('dmt_transactions.created_at', [$start_date, $end_date])->where('dmt_transactions.user_id', Auth::user()->id)
                  ->join('remitters','dmt_transactions.remitter_id','=','remitters.id')
                  ->join('remitter_beneficiaries','dmt_transactions.beneficiary_id','=','remitter_beneficiaries.id')->orderBy('dmt_transactions.id', 'DESC')
                  ->select('dmt_transactions.bank_transaction_id as BankTransactionId','dmt_transactions.reference_number as ReferenceNumber','dmt_transactions.created_at as TransactionDate','remitters.name as SenderName','remitters.phone_no as MobileNo','remitter_beneficiaries.name as BeneficiaryName','remitter_beneficiaries.account_number as AccountNumber','dmt_transactions.amount as TotalAmount','dmt_transactions.status as Status','dmt_transactions.remarks as BankRemarks')->get()->toArray();
                  }    
              }    
        $export_csv= new Export();
        $export_csv->exportData($records,"transaction-report-");
    }
}
