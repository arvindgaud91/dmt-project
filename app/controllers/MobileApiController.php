<?php

use Acme\Auth\Auth;
use Carbon\Carbon;
use Acme\Helper\Rabbit;

class MobileApiController extends BaseController {

    public function getAgentDashboard ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      //@TODO: Check what the three fields mean - Ashwin
      $dashboard['withdraw'] = AepsTransaction::where('user_id', Auth::user()->id)
        ->where('type', 2)
        ->where('result', 1)
        ->where('status', 4)
        ->whereDate('created_at', '=', Carbon::today()->toDateString())
        ->sum('amount');
      $dashboard['deposit'] = AepsTransaction::where('user_id', Auth::user()->id)
        ->where('type', 1)
        ->where('result', 1)
        ->where('status', 4)
        ->whereDate('created_at', '=', Carbon::today()->toDateString())
        ->sum('amount');
      $dashboard['balance'] = Vendor::where('user_id', Auth::user()->id)->first()->balance;
      return Response::json($dashboard, 200);
    }


    public function getDistributorDashboard ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 400);
      $dashboard['totalNoOfAgents'] = Vendor::where('parent_id', Auth::user()->id)
        ->where('type', 1)
        ->count();
      $dashboard['balanceWithAgents'] = Vendor::where('parent_id', Auth::user()->id)
        ->where('type', 1)
        ->sum('balance');
      return Response::json($dashboard, 200);
    }

    public function getAgents ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 400);
      $agents = Vendor::where('parent_id', Auth::user()->id)
        ->where('type', 1)
        -with('user')
        ->get();
      $response = array_map(function ($agent) {
        return (object) [
          'id' => $agent->id, // @TODO: WHat is agent id? Is it csr_id? It is id.
          'name' => $agent->user->name,
          'RBLId' => $agent->csr_id,
          'email' => $agent->user->email,
          'joiningDate' => Carbon::parse($agent->created_at)->format('d-m-Y'),
          'mobile' => $agent->user->phone_no,
          'balance' => $agent->balance,
          'distributor' => $agent->parent_id,
          'superDistributor' => Vendor::where('user_id', $agent->parent_id)->first()->parent_id
        ];
      }, $agents);
      return Response::json(['agents' => $response], 200);
    }

    public function postCreditAgentWallet ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 400);
      $agent_code = Input::get('code'); // @TODO: Confirm what agentCode is. Assumed to be id. It is csr_id.
      $amount = Input::get('amount');

      $agentSubmitted = Vendor::where('user_id', $agent_code);
      if ($agentSubmitted->parent_id != Auth::user()->id)
        return Response::json('Agent not mapped to this Distributor', 400);

      $vendor = Vendor::where('user_id', $agentSubmitted->parent_id)->lockForUpdate()->first();
      if ($vendor->balance < $amount) return Response::json('Insufficient Funds', 420);

      $vendor->balance -= $amount;
      $vendor->save();
      $debit = WalletTransaction::create(['user_id' => $vendor->user_id, 'transaction_type' => 0, 'amount' => $amount, 'balance' => $vendor->balance]);
      // @TODO: Check all code for places where vendor->id was used instead of vendor->user_id
      $agent = Vendor::where('user_id', $agentSubmitted->id)->lockForUpdate()->first();
      $agent->balance += $amount;
      $agent->save();
      $credit = WalletTransaction::create(['user_id' => $agent->id, 'transaction_type' => 1, 'amount' => $amount, 'balance' => $agent->balance]);

      WalletAction::create([
        'user_id' => $agent->user_id,
        'counterpart_id' => $vendor->user_id,
        'amount' => $request->amount,
        'status' => 1,
        'credit_id' => $credit->id,
        'wallet_request_id' => $request->id,
        'type' => 0,
        'admin' => false,
        'automatic' => false
      ]);

      WalletAction::create([
        'user_id' => $vendor->user_id,
        'counterpart_id' => $agent->user_id,
        'amount' => $request->amount,
        'status' => 1,
        'debit_id' => $debit->id,
        'wallet_request_id' => $request->id,
        'type' => 0,
        'admin' => false,
        'automatic' => false
      ]);

      return Response::json('Success', 200);
    }

    public function postDebitAgentWallet ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 400);
      $agent_code = Input::get('code'); // @TODO: Confirm what agentCode is. Assumed to be id. Its is csr_id.
      $amount = Input::get('amount');

      $agentSubmitted = Vendor::where('user_id', $agent_code);
      if ($agentSubmitted->parent_id != Auth::user()->id)
      return Response::json('Agent not mapped to this Distributor', 400);

      $agent = Vendor::where('user_id', $agentSubmitted->id)->lockForUpdate()->first();
      if ($agent->balance < $amount) return Response::json('Insufficient Funds', 420);

      $agent->balance -= $amount;
      $agent->save();
      $debit = WalletTransaction::create(['user_id' => $agent->user_id, 'transaction_type' => 0, 'amount' => $amount, 'balance' => $agent->balance]);
      // @TODO: Check all code for places where vendor->id was used instead of vendor->user_id
      $vendor = Vendor::where('user_id', $agent->parent_id)->lockForUpdate()->first();
      $vendor->balance += $amount;
      $vendor->save();
      $credit = WalletTransaction::create(['user_id' => $vendor->id, 'transaction_type' => 1, 'amount' => $amount, 'balance' => $vendor->balance]);

      WalletAction::create([
        'user_id' => $agent->user_id,
        'counterpart_id' => $vendor->user_id,
        'amount' => $request->amount,
        'status' => 1,
        'debit_id' => $debit->id,
        'wallet_request_id' => $request->id,
        'type' => 0,
        'admin' => false,
        'automatic' => false
      ]);

      WalletAction::create([
        'user_id' => $vendor->user_id,
        'counterpart_id' => $agent->user_id,
        'amount' => $request->amount,
        'status' => 1,
        'credit_id' => $credit->id,
        'wallet_request_id' => $request->id,
        'type' => 0,
        'admin' => false,
        'automatic' => false
      ]);

      return Response::json('Success', 200);
    }

    public function getTransactions () {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      $offset = Input::get('offset');
      $limit = Input::has('limit') ? Input::get('limit') : 5; // If no limit is provided, return last 5 transactions
      $transactions = AepsTransaction::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->skip($offset)->take($limit)->get();
      $response = array_map(function ($transaction) {
        return (object) [
          'id' => $transaction->id,
          'aadharNo' => $transaction->aadhar_no,
          'serviceMod' => $this->getTransactionType($transaction->type)['type'],
          'bank' => Bank::find($transaction->bank_id)->iin,
          'amount' => $transaction->amount,
          'timestamp' => Carbon::parse($transaction->created_at)->format('d-m-Y H:i:s'),
          'balance' => $transaction->balance,
          'status' => $transaction->result == 1 ? "Success" : ($transaction->result == 0 ? "Fail" : ''),
          'remarks' => '' //@TODO: Add remarks to aeps_transactions table
        ];
      }, json_decode($transactions));
      return Response::json(['transactions' => $response], 200);
    }

    public function getTransactionsByDistributor () {
      if (! Auth::user()) return Response::json("Invalid Token", 400);
      $offset = Input::get('offset');
      $limit = Input::has('limit') ? Input::get('limit') : 5;
      $transactions = AepsTransaction::whereIn('user_id', Vendor::where('parent_id', Auth::user()->id)->lists('user_id'))
        ->orderBy('id', 'DESC')
        ->skip($offset)
        ->take($limit)
        ->get();
      $response = array_map(function ($transaction) {
        return (object) [
          'id' => $transaction->id,
          'aadharNo' => $transaction->aadhar_no,
          'serviceMod' => $this->getTransactionType($transaction->type)['type'],
          'bank' => Bank::find($transaction->bank_id)->iin, //@TODO: COnfirm if bank means bank code. It is bank_id.
          'amount' => $transaction->amount,
          'timestamp' => Carbon::parse($transaction->created_at)->format('d-m-Y H:i:s'),
          'balance' => $transaction->balance,
          'status' => $transaction->result == 1 ? "Success" : ($transaction->result == 0 ? "Fail" : ''),
          'remarks' => '' //@TODO: Add remarks to aeps_transactions table
        ];
      }, json_decode($transactions));
      return Response::json(['transactions' => $response], 200);
    }

    public function getBanks ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      $banks = Bank::get();
      $response = array_map(function ($bank) {
        return (object) [
          'code' => $bank->iin,
          'bankName' => $bank->name
        ];
      }, json_decode($banks));
      return Response::json(['banks' => $response], 200);
    }

    public function postGenerateBalanceEnquiry ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      if (! Input::has('aadharNo') || ! Input::has('bankCode')) return Response::json("Invalid Parameters", 420);

      if (! $this->limitTransactions(Input::get('aadharNo'), 0))
        return Response::json(['message' => 'Maximum number of Balance Enquiries for Aadhar number '.Input::get('aadharNo').' reached'], 422);

      $aadhar_no = Input::get('aadharNo');
      $bank_iin = Input::get('bankCode');
      $bank = Bank::where('iin', $bank_iin)->first();
      if ($bank == null) return Response::json("Invalid Parameters", 420);
      $transaction = AepsTransaction::create([
        'user_id' => Auth::user()->id,
        'aadhar_no' => $aadhar_no,
        'bank_id' => $bank->id,
        'type' => 0,
        'status' => 0,
        'stan' => incrementalHash()
      ]);
      return Response::json(['transactionId' => $transaction->id, 'timestamp' => Carbon::parse($transaction->created_at)->format('d-m-Y H:i:s'), 'aadharNo' => $transaction->aadhar_no, 'bank' => $bank->iin], 200);
    }

    public function postGenerateDeposit ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      if (! Input::has('aadharNo') || ! Input::has('bankCode') || ! Input::has('amount')) return Response::json("Invalid Parameters", 420);

      if (! $this->limitTransactions(Input::get('aadharNo'), 1))
      return Response::json(['message' => 'Maximum number of Deposits for Aadhar number '.Input::get('aadharNo').' reached'], 422);

      $vendor = Vendor::where('user_id', Auth::user()->id)->first();
      if ($vendor->balance < Input::get('amount'))
        return Response::json(['message' => 'Insufficient Balance'], 422);

      if (Input::get('amount') > 10000)
        return Response::json(['message' => 'Transaction Amount limit exceeded'], 422);          

      $aadhar_no = Input::get('aadharNo');
      $bank_iin = Input::get('bankCode');
      $amount = Input::get('amount');
      $bank = Bank::where('iin', $bank_iin)->first();
      if ($bank == null) return Response::json("Invalid Parameters", 420);
      $transaction = AepsTransaction::create([
        'user_id' => Auth::user()->id,
        'aadhar_no' => $aadhar_no,
        'bank_id' => $bank->id,
        'amount' => $amount,
        'type' => 1,
        'status' => 0,
        'stan' => incrementalHash()
      ]);
      return Response::json(['transactionId' => $transaction->id, 'timestamp' => Carbon::parse($transaction->created_at)->format('d-m-Y H:i:s'), 'aadharNo' => $transaction->aadhar_no, 'bank' => $bank->iin, 'amount' => $transaction->amount], 200);
    }

    public function postGenerateWithdraw ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      if (! Input::has('aadharNo') || ! Input::has('bankCode') || ! Input::has('amount')) return Response::json("Invalid Parameters", 420);

      if (! $this->limitTransactions(Input::get('aadharNo'), 2))
        return Response::json(['message' => 'Maximum number of Withdrawals for Aadhar number '.Input::get('aadharNo').' reached'], 422);

      if (Input::get('amount') > 10000)
        return Response::json(['message' => 'Transaction Amount limit exceeded'], 422);

      $aadhar_no = Input::get('aadharNo');
      $bank_iin = Input::get('bankCode');
      $amount = Input::get('amount');
      $bank = Bank::where('iin', $bank_iin)->first();
      if ($bank == null) return Response::json("Invalid Parameters", 420);
      $transaction = AepsTransaction::create([
        'user_id' => Auth::user()->id,
        'aadhar_no' => $aadhar_no,
        'bank_id' => $bank->id,
        'amount' => $amount,
        'type' => 2,
        'status' => 0,
        'stan' => incrementalHash()
      ]);
      return Response::json(['transactionId' => $transaction->id, 'timestamp' => Carbon::parse($transaction->created_at)->format('d-m-Y H:i:s'), 'aadharNo' => $transaction->aadhar_no, 'bank' => $bank->iin, 'amount' => $transaction->amount], 200);
    }

    public function postConfirmTransaction ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      if (! Input::has('aadharNo') || ! Input::has('bankCode') || ! Input::has('fingerPrint') || ! Input::has('transactionId')) return Response::json("Invalid Parameters", 420);
      $aadhar_no = Input::get('aadharNo');
      $bank_iin = Input::get('bankCode');
      $fingerprint = Input::get('fingerPrint');
      $transaction = AepsTransaction::find(Input::get('transactionId'));
      $bank = Bank::where('iin', $bank_iin)->first();
      if (! $bank) return Response::json("Invalid Parameters", 420);
      if (! $transaction) return Response::json("Invalid Parameters", 420);
      if ($transaction->aadhar_no != $aadhar_no || $transaction->bank_id != $bank->id) return Response::json("Invalid Parameters", 420);
      $transactionPayload = ['aadhar_no' => $aadhar_no, 'bank_iin' => $bank->iin, 'fingerprint' => $fingerprint, 'amount' => $transaction->amount, 'stan' => $transaction->stan, 'action' => $this->getAction($transaction->type)['type']];
      $vendor = Vendor::where('user_id', Auth::user()->id)->first();
      $transaction->status = $transaction->status == 0 ? 1 : $transaction->status;
      $transaction->save();
      if ($this->transact($transactionPayload, $vendor, $transaction->id)) {
        return Response::json([
          'transactionId' => $transaction->id,
          'aadharNo' => $transactionPayload['aadhar_no'],
        ], 200);
      }
      return Response::json("Transaction request to queue failed.", 500);
    }

    public function postTransactionStatus ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      if (! Input::has('transactionId')) return Response::json("Invalid Parameters", 420);
      $transaction = AepsTransaction::find(Input::get('transactionId'));
      if (! $transaction) return Response::json("Invalid Parameters", 420);
      $bank = Bank::where('id', $transaction->bank_id)->first();
      $response = AadhaarCode::where('response_code', $transaction->result_code)->first();
      if (! $response && $transaction->bank_response_code && $transaction->bank_response_code != '00')
        $response = 'Rejected at issuer bank: '.$transaction->bank_response_code;
      $remarks = $transaction->remarks;
      if ($transaction->remarks == 'queuefail') $remarks = 'Failed: Error';
      if ($transaction->status == 4) {
        return Response::json([
          'status' => 'Complete',
          'transactionId' => $transaction->id,
          'timestamp' => Carbon::parse($transaction->created_at)->format('d-m-Y H:i:s'),
          'aadharNo' => $transaction->aadhar_no,
          'bank' => $bank->iin,
          'balance' => $transaction->balance,
          'response' => $response ? $response->description : $transaction->remarks,
          'remark' => '',
          'serviceMod' => $this->getTransactionType($transaction->type)['type']
        ], 200);
      }
      return Response::json(['status' => 'Pending'], 200);
    }

    public function getModeOfTransfer ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      $modeOfTransferDict = Config::get('dictionary.MODE_OF_TRANSFER');
      $modes = [];
      foreach ($modeOfTransferDict as $key => $value) {
        array_push($modes, ['name' => $value, 'code' => $key]);
      }
      return Response::json(['modes' => $modes], 200);
    }

    public function postBalanceRequest ()
    {
      //@TODO: API expects no validation on server. Please change and validate.
      //@TODO: Check if value sent in request is bank_id or bank_iin
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      $balanceRequestSubmitted = $this->filterOnly(Input::all(), ['bankId', 'amount', 'referenceNumber', 'bankBranch', 'modeOfTransfer']);
      $bank = Bank::where('id', $balanceRequestSubmitted['bankId'])->first();
      $balanceRequest = new BalanceRequest([
        'bank' => $bank->id,
        'amount' => $balanceRequestSubmitted['amount'],
        'transfer_mode' => $balanceRequestSubmitted['modeOfTransfer'],
        'branch' => Input::has('bankBranch') ? $balanceRequestSubmitted['bankBranch'] : '',
        'reference_number' => $balanceRequestSubmitted['referenceNumber'],
        'service_id' => 0,
        'user_id' => Auth::user()->id
      ]);
      $balanceRequest->save();
      return Response::json("Success", 200);
    }

    public function postBalanceRequestByDistributor ()
    {
      //@TODO: API expects no validation on server. Please change and validate.
      //@TODO: Check if value sent in request is bank_id or bank_iin
      if (! Auth::user()) return Response::json("Invalid Token", 400);
      $balanceRequestSubmitted = $this->filterOnly(Input::all(), ['bankId', 'amount', 'referenceNumber', 'bankBranch', 'modeOfTransfer']);
      $balanceRequest = new BalanceRequest([
        'bank' => $balanceRequestSubmitted['bankId'],
        'amount' => $balanceRequestSubmitted['amount'],
        'transfer_mode' => $balanceRequestSubmitted['modeOfTransfer'],
        'branch' => $balanceRequestSubmitted['bankBranch'],
        'reference_number' => $balanceRequestSubmitted['referenceNumber'],
        'service_id' => 0,
        'user_id' => Auth::user()->id
      ]);
      $balanceRequest->save();
      return Response::json("Success", 200);
    }

    public function getBankAccountInfo ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      return Response::json(["bankAccounts" => [
        (object) [
          'accountName' => 'DIGITAL INDIA PAYMENTS LIMITED',
          'accountNo' => '039305008196',
          'ifscCode' => 'ICIC0000393',
          'bankName' => 'ICICI BANK LIMITED',
          'branch' => 'CIBD MUMBAI BRANCH',
          'bankId' => 7
          ]
        ]
      ]);
    }

    public function getWalletReports ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      $offset = Input::get('offset');
      $limit = Input::has('limit') ? Input::get('limit') : 5;
      $aeps_wallet_actions = AepsWalletAction::where('user_id', Auth::user()->id)
        ->orderBy('id', 'DESC')
        ->skip($offset)
        ->take($limit)
    		->with('debit')
    		->with('credit')
        ->with('transaction')
    		->get();
      $response = array_map(function ($action) {
        return (object) [
          'id' => $action->transaction->id,
          'aadharNo' => $action->transaction->aadhar_no,
          'serviceMod' => $this->getTransactionType($action->transaction_type)['type'],
          'amount' => $action->amount,
          'timestamp' => Carbon::parse($action->transaction->created_at)->format('d-m-Y H:i:s'),
          'balance' => $action->transaction->balance,
          'fees' => '',
          'credit' => isset($action->credit->id) ? $action->credit->amount : '',
          'debit' => isset($action->debit->id) ? $action->debit->amount : ''
        ];
      }, json_decode($aeps_wallet_actions));
      return Response::json(['transaction' => $response], 200);
    }

    public function getWalletReportsForDistributor ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 400);
      $offset = Input::get('offset');
      $limit = Input::has('limit') ? Input::get('limit') : 5;
      $aeps_wallet_actions = AepsWalletAction::whereIn('user_id', Vendor::where('parent_id', Auth::user()->id)->lists('user_id'))
        ->orderBy('id', 'DESC')
        ->skip($offset)
        ->take($limit)
    		->with('debit')
    		->with('credit')
        ->with('transaction')
    		->get();
      $response = array_map(function ($action) {
        return (object) [
          'id' => $action->transaction->id,
          // 'adharNo' => $action->transaction->aadhar_no,
          'serviceMod' => $this->getTransactionType($action->transaction_type)['type'],
          'amount' => $action->amount,
          'timestamp' => Carbon::parse($action->transaction->created_at)->format('d-m-Y H:i:s'),
          'balance' => $action->transaction->balance,
          'credit' => isset($action->credit->id) ? $action->credit->amount : '',
          'debit' => isset($action->debit->id) ? $action->debit->amount : ''
        ];
      }, json_decode($aeps_wallet_actions));
      return Response::json(['transaction' => $response], 200);
    }

    public function getBalanceRequests ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      $offset = Input::get('offset');
      $limit = Input::has('limit') ? Input::get('limit') : 5;
      $requests = BalanceRequest::where('user_id', Auth::user()->id)
        ->orderBy('id', 'DESC')
        ->skip($offset)
        ->take($limit)
        ->get();
      $response = array_map(function ($request) {
        return (object) [
          'id' => $request->id,
          'modeOfTransfer' => Config::get('dictionary.MODE_OF_TRANSFER')[$request->transfer_mode],
          'status' => $this->getStatusByKey($request->status),
          'amount' => $request->amount,
          'timestamp' => Carbon::parse($request->created_at)->format('d-m-Y H:i:s'),
          'bank' => Bank::find($request->bank)->name,
          'branch' => $request->branch, //@TODO: Branch is not compulsory. Verify it doesn't break any code.
          'referenceNo' => $request->reference_number
        ];
      }, json_decode($requests));
      return Response::json(['report' => $response], 200);
    }

    public function getBalanceRequestsByDistributor ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 400);
      $offset = Input::get('offset');
      $limit = Input::has('limit') ? Input::get('limit') : 5;
      $requests = BalanceRequest::where('user_id', Auth::user()->id)
        ->orderBy('id', 'DESC')
        ->skip($offset)
        ->take($limit)
        ->get();
      $response = array_map(function ($request) {
        return (object) [
          'id' => $request->id,
          'modeOfTransfer' => Config::get('dictionary.MODE_OF_TRANSFER')[$request->transfer_mode],
          'status' => $this->getStatusByKey($request->status),
          'amount' => $request->amount,
          'timestamp' => Carbon::parse($request->created_at)->format('d-m-Y H:i:s'),
          'bank' => Bank::find($request->bank)->name,
          'branch' => $request->branch, //@TODO: Branch is not compulsory. Verify it doesn't break any code.
          'referenceNo' => $request->reference_number
        ];
      }, json_decode($requests));
      return Response::json(['report' => $response], 200);
    }

    public function postTransactionDetails ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      $transaction_id = Input::only('transactionId');
      $transaction = AepsTransaction::where('id', $transaction_id)
        ->with('bank')
        ->with('user')
        ->with('user.vendor')
        ->first();
      if (! $transaction) return Response::json('Invalid Parameter', 420);
      $response = AadhaarCode::where('response_code', $transaction->result_code)->first();
      return Response::json([
        'transactionId' => $transaction->id,
        'timestamp' => Carbon::parse($transaction->created_at)->format('d-m-Y H:i:s'),
        'aadhaarNo' => $transaction->aadhar_no,
        'bankName' => $transaction->bank->name,
        'bankCode' => $transaction->bank->iin,
        'balance' => $transaction->balance,
        'response' => $response ? $response->description : '',
        'remark' => '',
        'serviceMod' => $this->getTransactionType($transaction->type)['type'],
        'terminalId' => $transaction->user->vendor->terminal_id,
        'agentId' => $transaction->user->vendor->csr_id,
        'BCName' => $transaction->user->name,
        'mATNreqID' => $transaction->user->vendor->device_id,
        'amount' => $transaction->amount
      ], 200);
    }

    public function getValidateTerminalId ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 444);
      $terminal_id = Input::header('terminalId');
      if (Auth::user()->vendorDetails->device_sr_no == $terminal_id)
        return Response::json("Success", 200);
      return Response::json('Invalid Terminal', 400);
    }

    public function getContactDetails ()
    {
      if (! Auth::user()) return Response::json("Invalid Token", 400);
      // @TODO: Get the following fields.
      return Response::json([
        'phone' => 1,
        'location' => [
          'addressLine1' => 1,
          'addressLine2' => 2,
          'addressLine3' => 3,
          'state' => 4,
          'city' => 5,
          'country' => 6,
          'zipcode' => 7
        ],
        'email' => 2,
        'web' => 3
      ], 200);
    }

    private function transact ($transactionSubmitted, $vendor, $transactionId)
    {
      $payload = array_merge($transactionSubmitted, [
        'csr_id' => $vendor->csr_id,
        'freshness_factor' => $vendor->freshness_factor,
        'transaction_id' => $transactionId,
        'device_id' => $vendor->device_id,
        'terminal_id' => $vendor->terminal_id
      ]);
      // send $payload to queue
      $rabbitQueue = new Rabbit('aeps_transactions');
      try {
        $rabbitQueue->publish($payload);
      } catch (Exception $err) {
        Log::info("Publish to Rabbit queue failed.");
        return false;
      }
      return true;
    }

    private function getStatusByKey ($key)
    {
      return $key == 0 ? "Fail" : ($key == 1 ? "Success" : '');
    }


    private function getTransactionType ($id) {
      $transactionDict = [
        0 => [
          'id' => 0,
          'type' => 'Balance Enquiry'
        ],
        1 => [
          'id' => 1,
          'type' => 'Deposit'
        ],
        2 => [
          'id' => 2,
          'type' => 'Withdraw'
        ]
      ];
      return $transactionDict[$id];
    }

    private function getAction ($id) {
      $transactionDict = [
        0 => [
          'id' => 0,
          'type' => 'balance_enquiry'
        ],
        1 => [
          'id' => 1,
          'type' => 'deposit'
        ],
        2 => [
          'id' => 2,
          'type' => 'withdraw'
        ]
      ];
      return $transactionDict[$id];
    }

    //@TODO: Implement limit of 5 successful transactions on an Aadhar Number on Mobile APIs.
    private function limitTransactions ($aadhar_no, $transaction_type) {
      return AepsTransaction::where('aadhar_no', $aadhar_no)
        ->where('type', $transaction_type)
        ->where('status', '>', 0)
        ->whereDate('created_at', '=', Carbon::today()->toDateString())
        ->count() < 5 ? true : false;
    }






}

?>
