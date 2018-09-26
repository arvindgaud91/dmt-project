<?php
use Acme\Auth\Auth;
use \Carbon\Carbon;
class AdminReportsController extends \BaseController {

	public function getAdminTransactionReport() {
		$tracker = Request::header('auth');
		$headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json',
	      'auth' => \Cookie::get('tracker')
	    ];
	    $body = \Unirest\Request\Body::json(['token' => $tracker]);
	    $response = \Unirest\Request::post(getenv('AUTH_URL').'/api/auth/v1/users/actions/admin/verify', $headers); 
	    if (! $response->body) return Response::json(['message' => 'Unauthorized access.'], 401);
	    $transactions = DmtTransaction::join('remitters','dmt_transactions.remitter_id','=','remitters.id')
                  ->join('remitter_beneficiaries','dmt_transactions.beneficiary_id','=','remitter_beneficiaries.id')->orderBy('dmt_transactions.id', 'DESC')
                  ->select('dmt_transactions.bank_transaction_id as BankTransactionId','dmt_transactions.user_id as UserId','dmt_transactions.reference_number as ReferenceNumber','dmt_transactions.created_at as TransactionDate','remitters.name as SenderName','remitters.phone_no as MobileNo','remitter_beneficiaries.name as BeneficiaryName','remitter_beneficiaries.account_number as AccountNumber','dmt_transactions.amount as TotalAmount','dmt_transactions.status as Status','dmt_transactions.utr_code as UTRCode','dmt_transactions.remarks as BankRemarks')->paginate(100);    	
      			return Response::json($transactions,200);  
	}

	public function getAdminWalletReport() {
		$tracker = Request::header('auth');
		$headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json',
	      'auth' => \Cookie::get('tracker')
	    ];
	    $body = \Unirest\Request\Body::json(['token' => $tracker]);
	    $response = \Unirest\Request::post(getenv('AUTH_URL').'/api/auth/v1/users/actions/admin/verify', $headers); 
	    if (! $response->body) return Response::json(['message' => 'Unauthorized access.'], 401);

	    $wallets=WalletTransaction::select('wallet_transactions.created_at as TransactionDate','wallet_transactions.user_id as UserId',
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
                    ->orderBy('wallet_transactions.id', 'DESC')
                   	->paginate(100);
        			return Response::json($wallets,200);
	}

	public function getAdminSuperDistributorCommissionsReport(){
	 	$tracker = Request::header('auth');
		$headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json',
	      'auth' => \Cookie::get('tracker')
	    ];
		$body = \Unirest\Request\Body::json(['token' => $tracker]);
	    $response = \Unirest\Request::post(getenv('AUTH_URL').'/api/auth/v1/users/actions/admin/verify', $headers); 
	    if (! $response->body) return Response::json(['message' => 'Unauthorized access.'], 401);
 	    	$user_id=DmtVendor::where('type',3)->lists('user_id');
 	    	// $commissions = DB::select('SELECT sum(wa.amount) as commission_amount,wa.user_id,wa.commission FROM wallet_actions wa where wa.user_id in('.implode(",",$user_id).') and wa.commission=1  GROUP by wa.user_id');
 	    		$commissions =DB::table('wallet_actions')
 	    		->select(DB::raw('sum(wallet_actions.amount) AS amount'),'user_id','commission')
 	    		->whereIn('user_id',$user_id)
 	    		->where('commission','=',1)
 	    		->groupBy('user_id')
 	    		->paginate(100);
 	  
 	    return Response::json($commissions,200);
	}

	public function getAdminDistributorCommissionsReport(){
	 	$tracker = Request::header('auth');
		$headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json',
	      'auth' => \Cookie::get('tracker')
	    ];
		$body = \Unirest\Request\Body::json(['token' => $tracker]);
	    $response = \Unirest\Request::post(getenv('AUTH_URL').'/api/auth/v1/users/actions/admin/verify', $headers); 
	    if (! $response->body) return Response::json(['message' => 'Unauthorized access.'], 401);
 	    	$user_id=DmtVendor::where('type',2)->lists('user_id');
 	    	// $commissions=DB::select('SELECT sum(wa.amount) as commission_amount,wa.user_id,wa.commission FROM wallet_actions wa where wa.user_id in('.implode(",",$user_id).') and wa.commission=1  GROUP by wa.user_id');
 	    	$commissions =DB::table('wallet_actions')
 	    		->select(DB::raw('sum(wallet_actions.amount) AS amount'),'user_id','commission')
 	    		->whereIn('user_id',$user_id)
 	    		->where('commission','=',1)
 	    		->groupBy('user_id')
 	    		->paginate(100); 
 	       return Response::json($commissions,200);
	}

//export functionality
	public function getAdminWalletExport(){
		$tracker = Request::header('auth');
		$headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json',
	      'auth' => \Cookie::get('tracker')
	    ];
	    $body = \Unirest\Request\Body::json(['token' => $tracker]);
	    $response = \Unirest\Request::post(getenv('AUTH_URL').'/api/auth/v1/users/actions/admin/verify', $headers); 

	    if (! $response->body) return Response::json(['message' => 'Unauthorized access.'], 401);
	    	if ((Input::get('from_date') && Input::get('to_date'))) {
                  $start_date = date('Y-m-d'. ' 00:00:00', strtotime(Input::get('from_date')));
                  $end_date = date('Y-m-d'. ' 23:59:59', strtotime(Input::get('to_date')));
                $queryObject = WalletTransaction::whereBetween('wallet_transactions.created_at', [$start_date, $end_date])
                  ->select('user_id','wallet_transactions.created_at as TransactionDate',
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
                    ->orderBy('wallet_transactions.id', 'DESC');

                    if (Input::get('user_id')){
	                 	$queryObject=$queryObject->where('user_id', Input::get('user_id'));
	                 }
					
					$wallets=$queryObject->get();

        			return Response::json($wallets,200);           		 
				}
				else
				{
					$wallets='';
		 			return Response::json($wallets,422);

				}
}

	public function getAdminTransactionExport()
	{
			$tracker = Request::header('auth');
			$headers = [
		      'Accept' => 'application/json',
		      'Content-Type' => 'application/json',
		      'auth' => \Cookie::get('tracker')
		    ];
		    $body = \Unirest\Request\Body::json(['token' => $tracker]);
		    $response = \Unirest\Request::post(getenv('AUTH_URL').'/api/auth/v1/users/actions/admin/verify', $headers); 
		    if (! $response->body) return Response::json(['message' => 'Unauthorized access.'], 401);
			if ((Input::get('from_date') && Input::get('to_date'))) {
                  $start_date = date('Y-m-d'. ' 00:00:00', strtotime(Input::get('from_date')));
                  $end_date = date('Y-m-d'. ' 23:59:59', strtotime(Input::get('to_date')));
	                  $queryObject = DmtTransaction::whereBetween('dmt_transactions.created_at', [$start_date, $end_date])
	                  ->join('remitters','dmt_transactions.remitter_id','=','remitters.id')
	                  ->join('remitter_beneficiaries','dmt_transactions.beneficiary_id','=','remitter_beneficiaries.id')->orderBy('dmt_transactions.id', 'DESC')
	                  ->select('dmt_transactions.bank_transaction_id as BankTransactionId','dmt_transactions.reference_number as ReferenceNumber','dmt_transactions.created_at as TransactionDate','remitters.name as SenderName','remitters.phone_no as MobileNo','remitter_beneficiaries.name as BeneficiaryName','remitter_beneficiaries.account_number as AccountNumber','dmt_transactions.amount as TotalAmount','dmt_transactions.status as Status','dmt_transactions.remarks as BankRemarks');

	                 if (Input::get('user_id')){
	                 	$queryObject=$queryObject->where('dmt_transactions.user_id', Input::get('user_id'));
	                 }
					
				$records=$queryObject->get()->toArray();
	                   return Response::json($records,200);
			}
			else
			{
				$records='';
	 			return Response::json($records,422);

			}
	}

		public function getAdminDistributorCommissionsExport()
			{
				$tracker = Request::header('auth');
				$headers = [
			      'Accept' => 'application/json',
			      'Content-Type' => 'application/json',
			      'auth' => \Cookie::get('tracker')
			    ];
			    $body = \Unirest\Request\Body::json(['token' => $tracker]);
			    $response = \Unirest\Request::get(getenv('AUTH_URL').'/api/auth/v1/users/actions/admin/verify', $headers); 
			    if (! $response->body) return Response::json(['message' => 'Unauthorized access.'], 401);
			    $user_id=DmtVendor::where('type',2)->lists('user_id');
				$commissions=DB::select('SELECT sum(wa.amount) as commission_amount,wa.user_id,wa.commission FROM wallet_actions wa where wa.user_id in('.implode(",",$user_id).') and wa.commission=1  GROUP by wa.user_id'); 
		 		return Response::json($commissions,422);	
			}

		public function getAdminSuperDistributorCommissionsExport()
		{
			$tracker = Request::header('auth');
			$headers = [
		      'Accept' => 'application/json',
		      'Content-Type' => 'application/json',
		      'auth' => \Cookie::get('tracker')
		    ];
		    $body = \Unirest\Request\Body::json(['token' => $tracker]);
		    $response = \Unirest\Request::get(getenv('AUTH_URL').'/api/auth/v1/users/actions/admin/verify', $headers); 
		    if (! $response->body) return Response::json(['message' => 'Unauthorized access.'], 401);
		    $user_id=DmtVendor::where('type',3)->lists('user_id');
			$commissions = DB::select('SELECT sum(wa.amount) as commission_amount,wa.user_id,wa.commission FROM wallet_actions wa where wa.user_id in('.implode(",",$user_id).') and wa.commission=1  GROUP by wa.user_id'); 		
			return Response::json($commissions,422);
			
		}	

		public function getBankDetails() {
		$tracker = Request::header('auth');
		$headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json',
	      'auth' => \Cookie::get('tracker')
	    ];
	    $body = \Unirest\Request\Body::json(['token' => $tracker]);
	    $response = \Unirest\Request::post(getenv('AUTH_URL').'/api/auth/v1/users/actions/admin/verify', $headers); 
	    if (! $response->body) return Response::json(['message' => 'Unauthorized access.'], 401);
	    		$banks = DmtBank::orderBy('dmt_banks.id', 'DESC')
                  ->select('dmt_banks.id as Id','dmt_banks.imps as imps','dmt_banks.neft as neft','dmt_banks.name as BankName','dmt_banks.created_at as CreatedDate')->paginate(100);  	
      			return Response::json($banks,200);  
		}
		public function updateBankStatus() {
		$tracker = Request::header('auth');
		$headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json',
	      'auth' => \Cookie::get('tracker')
	    ];
	    $body = \Unirest\Request\Body::json(['token' => $tracker]);
	    $response = \Unirest\Request::post(getenv('AUTH_URL').'/api/auth/v1/users/actions/admin/verify', $headers); 
	    if (! $response->body) return Response::json(['message' => 'Unauthorized access.'], 401);

	    	
	    	$bank_status = DmtBank::where('id',Input::get('id'))->update([Input::get('type')=>Input::get('value')]);  
	
      			return Response::json($bank_status,200);  
		}

	public function getAdminDMTLastDayclosingReport() 
	{
		$tracker = Request::header('auth');
		$headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json',
	      'auth' => \Cookie::get('tracker')
	    ];
	    $body = \Unirest\Request\Body::json(['token' => $tracker]);
	    $response = \Unirest\Request::post(getenv('AUTH_URL').'/api/auth/v1/users/actions/admin/verify', $headers); 
	    if (! $response->body) return Response::json(['message' => 'Unauthorized access.'], 401);
	    $dt = Carbon::yesterday();
	    $dt = $dt->endOfDay();
	    $transactions = DB::select("select dmt_vendors.user_id,dmt_vendors.bc_agent,wallet_transactions.balance,wallet_transactions.created_at from dmt_vendors join wallet_transactions on dmt_vendors.user_id =wallet_transactions.user_id WHERE dmt_vendors.type IN(1,2,3) AND wallet_transactions.id = (SELECT max(id) FROM `wallet_transactions` WHERE `created_at` <= '".$dt."'  AND user_id = dmt_vendors.user_id)");
      	return Response::json($transactions,200);  
	}

	public function getAdminLastDayClosingExports()
	{
		$tracker = Request::header('auth');
		$headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json',
	      'auth' => \Cookie::get('tracker')
	    ];
	    $body = \Unirest\Request\Body::json(['token' => $tracker]);
	    $response = \Unirest\Request::post(getenv('AUTH_URL').'/api/auth/v1/users/actions/admin/verify', $headers); 
	    if (! $response->body) return Response::json(['message' => 'Unauthorized access.'], 401);
		if (Input::get('from_date')) {
              $end_date = date('Y-m-d'. ' 23:59:59', strtotime(Input::get('from_date')));

              $transactions = DB::select("select dmt_vendors.user_id,dmt_vendors.bc_agent,wallet_transactions.balance,wallet_transactions.created_at from dmt_vendors join wallet_transactions on dmt_vendors.user_id =wallet_transactions.user_id WHERE dmt_vendors.type IN(1,2,3) AND wallet_transactions.id = (SELECT max(id) FROM `wallet_transactions` WHERE `created_at` <= '".$end_date."'  AND user_id = dmt_vendors.user_id)");
              $records=$transactions;
            return Response::json($records,200);
		}
		else
		{
			$records='';
 			return Response::json($records,422);
		}
	}

	public function getSuspiciousAgent ()
  	{ 
	   $data=DmtVendor::where('balance','>=',200)->where('type','>=',1)->get();
	   
	  	return Response::json($data,200);
 	}


	public function getAdminAgentAverageTransactionReports() 
	{
		$tracker = Request::header('auth');
		$headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json',
	      'auth' => \Cookie::get('tracker')
	    ];
	    $body = \Unirest\Request\Body::json(['token' => $tracker]);
	    $response = \Unirest\Request::post(getenv('AUTH_URL').'/api/auth/v1/users/actions/admin/verify', $headers); 
	    if (! $response->body) return Response::json(['message' => 'Unauthorized access.'], 401);

	    $date = Carbon::today();
	    $date->subDays(7);
	    $user_id=DmtVendor::where('type',1)->lists('user_id');

		$transactions = DB::table(DB::raw('dmt_transactions t1'))
                     ->leftJoin(DB::raw('dmt_transactions t2'), function($join)
                         {
                             $join->on(DB::raw('t1.user_id'), '=', DB::raw('t2.user_id'));
                             $join->where('t2.created_at','>=',Carbon::today()->toDateString());
                         })
                     ->select(DB::raw('t1.user_id,avg(t1.amount) AS avg_amount,max(t2.amount) AS max_amount,(max(t2.amount)-avg(t1.amount)) AS diff'))
                     ->whereIn('t1.user_id',$user_id)
			    	 ->where('t1.created_at','>=',$date->toDateTimeString())
			    	 ->orderByRaw('diff DESC')
			    	 ->groupBy('user_id')
                     ->paginate(100);
      	return Response::json($transactions,200);  
	}

	public function getAdminAgentAverageTransactionExports()
	{
		$tracker = Request::header('auth');
		$headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json',
	      'auth' => \Cookie::get('tracker')
	    ];
	    $body = \Unirest\Request\Body::json(['token' => $tracker]);
	    $response = \Unirest\Request::post(getenv('AUTH_URL').'/api/auth/v1/users/actions/admin/verify', $headers); 
	    if (! $response->body) return Response::json(['message' => 'Unauthorized access.'], 401);

	    $date = Carbon::today();
	    $date->subDays(7);
	    $user_id=DmtVendor::where('type',1)->lists('user_id');
	    $transactions = DB::table(DB::raw('dmt_transactions t1'))
                     ->leftJoin(DB::raw('dmt_transactions t2'), function($join)
                         {
                             $join->on(DB::raw('t1.user_id'), '=', DB::raw('t2.user_id'));
                             $join->where('t2.created_at','>=',Carbon::today()->toDateString());
                         })
                     ->select(DB::raw('t1.user_id,avg(t1.amount) AS avg_amount,max(t2.amount) AS max_amount,(max(t2.amount)-avg(t1.amount)) AS diff'))
                     ->whereIn('t1.user_id',$user_id)
			    	 ->where('t1.created_at','>=',$date->toDateTimeString())
			    	 ->orderByRaw('diff DESC')
			    	 ->groupBy('user_id')
			    	 ->get();
	    $records=$transactions;
	    return Response::json($records,200);
	}

	public function getAdminClosingBalanceExports()
	{
		$tracker = Request::header('auth');
		$headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json',
	      'auth' => \Cookie::get('tracker')
	    ];
	    $body = \Unirest\Request\Body::json(['token' => $tracker]);
	    $response = \Unirest\Request::post(getenv('AUTH_URL').'/api/auth/v1/users/actions/admin/verify', $headers); 
	    if (! $response->body) return Response::json(['message' => 'Unauthorized access.'], 401);
		if (Input::get('end_date')) {
			$end_date = Input::get('end_date');
			$transactions = DB::select(DB::raw("select	uv.user_id as userId,uv.type,uv.bc_agent,lt.balance AS lastdayclosing ,lt.created_at AS CreatedAt from dmt_vendors uv, last_day_closing_balance lt WHERE uv.user_id = lt.user_id AND uv.type IN(1,2,3) AND lt.id = (select max(id) from last_day_closing_balance WHERE DATE(created_at) ='$end_date' AND user_id = uv.user_id)"));

              $records=$transactions;
            return Response::json($records,200);
		}
		else
		{
			$records='';
 			return Response::json($records,422);
		}
	}

	public function getBalanceRecoveryDmt(){

		$tracker = Request::header('auth');
		$headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json',
	      'auth' => \Cookie::get('tracker')
	    ];
	    $body = \Unirest\Request\Body::json(['token' => $tracker]);
	    $response = \Unirest\Request::post(getenv('AUTH_URL').'/api/auth/v1/users/actions/admin/verify', $headers); 
	    if (! $response->body) return Response::json(['message' => 'Unauthorized access.'], 401);
	    	$recoveryAgents = Vendor::whereIn('user_id',array(2255,4288,5095,5229,5428,5478,5479,5481,5508,5585,5897,5956,6000,6279,6324,6636,6648,7623,7674,8039,8040,8043,8046,8142,8162,9450,10567,4424,4612,4866,5277,5384,5385,5858,5905,6052,6815,7735,8358,11084,11849))->orderBy('dmt_vendors.id','desc')->get();
	    	return Response::json($recoveryAgents,200);
	}

}
