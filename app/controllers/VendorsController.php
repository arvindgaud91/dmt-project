<?php
use Acme\Auth\Auth;
use Acme\Helper\GateKeeper;

/**
*
*/ 
class VendorsController extends BaseController
{

	function __construct()
	{
		
	}

	public function postAddDmtVendor ()
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
	    $dmtVendorSubmitted = $this->filterOnly(Input::all(), ['user_id', 'bc_agent', 'type', 'parent_id','asm_id']);
	    $dmt_vendor = DmtVendor::create($dmtVendorSubmitted);
	    return Response::json($dmt_vendor, 200);
	}

	public function postUpdateDmtVendor ($user_id)
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
	    $dmtVendorSubmitted = $this->filterOnly(Input::all(), ['user_id', 'bc_agent', 'type', 'parent_id', 'asm_id']);
	    $dmt_vendor = DmtVendor::where('user_id', $user_id)->first();
	    if (! $dmt_vendor) return Response::json(['message' => 'Dmt Vendor not found'], 422);
	    $updated = DmtVendor::where('user_id', $user_id)->update($dmtVendorSubmitted);
	    return Response::json("", 200);
	}

	public function getDmtVendor ($user_id)
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
	    $dmt_vendor = DmtVendor::where('user_id', $user_id)->first();
	    if (! $dmt_vendor) return Response::json(['message' => 'Dmt Vendor not found', 'code' => 1], 422);
	    return Response::json($dmt_vendor, 200);
	}
	
	public function deleteDmtVendor ($user_id)
	{
		// Verify admin request
		$tracker = Request::header('auth');
		$headers = [
	      'Accept' => 'application/json',
	      'Content-Type' => 'application/json',
	      'auth' => \Cookie::get('tracker')
	    ];
	    $body = \Unirest\Request\Body::json(['token' => $tracker]);
	    $response = \Unirest\Request::post(getenv('AUTH_URL').'/api/auth/v1/users/actions/admin/verify', $headers, $body); 
	    if (! $response->body) return Response::json(['message' => 'Unauthorized access.'], 401);

	    // Actual controller logic
	    $dmt_vendor = DmtVendor::where('user_id', $user_id)->first();
	    if (! $dmt_vendor) return Response::json(['message' => 'Dmt Vendor not found'], 422);
	    return Response::json($dmt_vendor->delete(), 200);
	}

	public function getDmtVendors ()
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

	    if (! Input::has('type')) return Response::json(['message' => 'Missing vendor type'], 422);
	    $vendors = DmtVendor::where('type', $this->getVendorType(Input::get('type'))['id'])->get();
	    return Response::json($vendors, 200);
	}

	public function getDmtVendorListData ()
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

	    if (! Input::has('type')) return Response::json(['message' => 'Missing vendor type'], 422);
	    $vendors = DmtVendor::where('type', $this->getVendorType(Input::get('type'))['id'])
	    ->orderBy('user_id','DESC')
	    ->get();
	    return Response::json($vendors, 200);
	}

	public function getDmtVendorsPaginate ()
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

	    if (! Input::has('type')) return Response::json(['message' => 'Missing vendor type'], 422);
	    $queryObj = DmtVendor::where('type', $this->getVendorType(Input::get('type'))['id']);

	    if (Input::has('searchType') && Input::has('queryString')) {
      
      if (Input::get('searchType') == 'id')
        $queryObj->where('user_id', Input::get('queryString'));
      
      if (Input::get('searchType') == 'bc_agent')
        $queryObj->where('bc_agent', Input::get('queryString'));
    }
    	$vendors = $queryObj->orderBy('user_id','DESC')->paginate(20);
	    return Response::json($vendors, 200);
	}

	private function getVendorType ($type)
	{
		$dict = [
			'Agent' => [
				'id' => 1,
				'type' => $type,
				'parent_type_id' => 2,
				'parent' => 'Distributor'
			],
			'Distributor' => [
				'id' => 2,
				'type' => $type,
				'parent_type_id' => 3,
				'parent' => 'Super Distributor'
			],
			'Super Distributor' => [
				'id' => 3,
				'type' => $type,
			],
			'Sales Executive' => [
				'id' => 4,
				'type' => $type,
				'parent_type_id' => 5,
				'parent' => 'Area Sales Officer'
			],
			'Area Sales Officer' => [
				'id' => 5,
				'type' => $type,
				'parent_type_id' => 6,
				'parent' => 'Area Sales Manager'
			],
			'Area Sales Manager' => [
          		'id' => 6,
          		'type' => $type,
          		'parent_type_id' => 7,
          		'parent' => 'Cluster Head'
      		],
      		'Cluster Head' => [
          		'id' => 7,
          		'type' => $type,
          		'parent_type_id' => 10,
          		'parent' => 'Cluster Head'
      		],
      		'State Head' => [
          		'id' => 10,
          		'type' => $type,
          		'parent_type_id' => 11,
          		'parent' => 'Regional Head'
      		],
      		'Regional Head' => [
          		'id' => 11,
          		'type' => $type,
     		 ]      
		];
		return $dict[$type];
	}

	public function getAgentTransactionReport($id)
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
     		$transactions = DmtTransaction::where('dmt_transactions.user_id',$id)
     			  ->join('remitters','dmt_transactions.remitter_id','=','remitters.id')
                  ->join('remitter_beneficiaries','dmt_transactions.beneficiary_id','=','remitter_beneficiaries.id')
                  ->orderBy('dmt_transactions.id', 'DESC')
                  ->select('dmt_transactions.bank_transaction_id as BankTransactionId','dmt_transactions.reference_number as ReferenceNumber','dmt_transactions.created_at as TransactionDate','remitters.name as SenderName','remitters.phone_no as MobileNo','remitter_beneficiaries.name as BeneficiaryName','remitter_beneficiaries.account_number as AccountNumber','dmt_transactions.amount as TotalAmount','dmt_transactions.status as Status','dmt_transactions.remarks as BankRemarks')
                  ->paginate(10);   	 
      		return Response::json($transactions,200);  
	}

	public function getAgentWalletReport($id)
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
        $wallets = DmtWalletAction::join('dmt_transactions','dmt_transactions.id','=','dmt_wallet_actions.transaction_id')
                   ->select('dmt_transactions.reference_number as ReferenceNumber','dmt_wallet_actions.created_at as TransactionDate',DB::raw("(if(dmt_wallet_actions.debit_id !=0 and dmt_wallet_actions.credit_id=0,CONCAT('-',(SELECT amount FROM wallet_transactions WHERE wallet_transactions.id=dmt_wallet_actions.debit_id)),CONCAT('+',
                    (SELECT amount FROM wallet_transactions WHERE wallet_transactions.id=dmt_wallet_actions.credit_id)))) as Amount"),
                    DB::raw("(if(dmt_wallet_actions.debit_id !=0 and dmt_wallet_actions.credit_id=0,CONCAT('',(SELECT balance FROM wallet_transactions WHERE wallet_transactions.id=dmt_wallet_actions.debit_id)),CONCAT('',
                    (SELECT balance FROM wallet_transactions WHERE wallet_transactions.id=dmt_wallet_actions.credit_id)))) as Balance"),
                     DB::raw("(if(dmt_wallet_actions.debit_id !=0 and dmt_wallet_actions.credit_id=0,CONCAT('Transaction'),CONCAT('Transaction'))) as Activity"))
                    ->orderBy('dmt_wallet_actions.id', 'DESC')
                    ->paginate(10);
        return Response::json($wallets,200);
	}

	// public function getAgentCommissionsReport(){
	// 	$headers = [
 //        'Accept' => 'application/json',
 //        'Content-Type' => 'application/json',
 //        'auth' => \Cookie::get('tracker')
 //      ];
 //      $response = \Unirest\Request::get(getenv('DMT_URL').'/api/v1/commissions', $headers); 
	// }

public function creditamount($user_id)
{
 

	if (! Input::has('amount')) return Resposne::json(['message' => 'Missing important information', 'code' => 0], 422);
		$creditRequest = $this->filterOnly(Input::all(), ['amount', 'remarks']);
		$creditRequest['remarks'] = Input::has('remarks') ? $creditRequest['remarks'] : '';


		$dmt_vendor = DmtVendor::where('user_id','=',$user_id)->first();
        $dmt_vendor->balance += Input::get('amount');
        $dmt_vendor->save();

      
       $debit_tx = WalletTransaction::create([
          'user_id' => $user_id,
          'transaction_type' => 1,
          'activity' =>'Credit-Wallet',
          'narration' =>$creditRequest['remarks'],
          'amount' =>Input::get('amount'),
          'balance' => $dmt_vendor->balance
        ]);

      DmtWalletAction::create([
          'user_id' => $user_id,
          'amount' => Input::get('amount'),
          'credit_id' => $debit_tx->id,
          'status' => 1,
          'transaction_id' => '00000',
          'transaction_type' => 1,
          'remarks' => $creditRequest['remarks']
        ]);

      return Response::json('', 200);	
}
  

public function debitamount($user_id)
{
 

	if (! Input::has('amount')) return Resposne::json(['message' => 'Missing important information', 'code' => 0], 422);
		$creditRequest = $this->filterOnly(Input::all(), ['amount', 'remarks']);
		$creditRequest['remarks'] = Input::has('remarks') ? $creditRequest['remarks'] : '';


		$dmt_vendor = DmtVendor::where('user_id','=',$user_id)->first();
        $dmt_vendor->balance -= Input::get('amount');
        $dmt_vendor->save();


  $debit_tx = WalletTransaction::create([
          'user_id' => $user_id,
          'transaction_type' => 0,
          'activity' =>'Debit-Wallet',
          'narration' =>$creditRequest['remarks'],
          'amount' =>Input::get('amount'),
          'balance' => $dmt_vendor->balance
        ]);


      DmtWalletAction::create([
          'user_id' => $user_id,
          'amount' => Input::get('amount'),
          'debit_id' => $debit_tx->id,
          'status' => 1,
          'transaction_id' => '00000',
          'transaction_type' => 0,
          'remarks' => $creditRequest['remarks']
        ]);

      return Response::json('', 200);			
}

}
