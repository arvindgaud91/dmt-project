<?php
require 'bootstrap/autoload.php';
$app = require 'bootstrap/start.php';
echo "started";
$app->boot();

use Acme\Helper\Rabbit;
use Acme\Contracts\SubscriptionHandler;
use Acme\ISO8583\ISO8583;
use Carbon\Carbon;

/**
 *
 */
class MATM extends SubscriptionHandler
{
  public function handle ($payload)
  {
    echo "in handle \n";
    // return $this->acknowledgeSuccess();
    try {
     if (! isset($payload->action))
        $this->acknowledgeSuccess();

      if ($payload->action == 'balance_enquiry') $this->balance($payload);
      if ($payload->action == 'deposit') $this->deposit($payload);
      if ($payload->action == 'withdraw') $this->withdraw($payload);

      return $this->acknowledgeSuccess();
    } catch (Exception $e) {
      $tx = AepsTransaction::find($payload->transaction_id);
      if (! $tx)
        return $this->acknowledgeSuccess();
      $tx->status = 4;
      $tx->result = 0;
      // @todo find better way
      echo $e->getMessage();
      $tx->remarks = 'queuefail';
      $tx->save();
      return $this->acknowledgeSuccess();
    }
  }

  private function balance ($payload)
  {
    echo "in balance \n";
    $transaction = AepsTransaction::find($payload->transaction_id);
    if ($transaction->status == 3 || $transaction->status == 4) return; // Do not send same transaction twice.
    if (Carbon::parse($transaction->updated_at)->diffInSeconds(Carbon::now('Asia/Kolkata')) > 15) {
      $transaction->status = 4;
      $transaction->result = 0;
      $transaction->remarks = 'Transaction Expired';
      $transaction->save();
      return;
    }
    $transaction->status = 1;
    date_default_timezone_set('Asia/Kolkata');
    $elements = [
      2 => '19'.$payload->bank_iin.'0'.$payload->aadhar_no,
      3 => '310000',
      4 => sprintf('%010u', $payload->amount).'00',
      7 => date('mdHis'),
      11 => $payload->stan,
      12 => date('His'),
      13 => date('md'),
      18 => "6012",
      41 => $payload->device_id,
      49 => '356',
      123 => '015210101213146000',
      127 => $this->fetchDE127($payload->fingerprint)
    ];
    $params = [
      'mti' => '0200',
      'elements' => $elements
    ];
    $isoFactory = new ISO8583();
    $isoMessage = $isoFactory->create($params);
    $transaction->status = 2;

    $rawResponse = $this->transact($payload, $isoMessage);
    \Log::info('iso8583Message: '.$isoMessage);
    $transaction->status = 3;
    $transaction->save();
    \Log::info('Bank Response: '.json_encode($rawResponse));

    $vendor = Vendor::where('csr_id', $payload->csr_id)->first();
    $vendor->freshness_factor = $rawResponse->nextFreshnessFactor;
    $vendor->save();

    $transaction->bank_response_code = $rawResponse->responseCode;

    if ($rawResponse->responseCode != '00') {
      $transaction->status = 4;
      $transaction->result = 0;
    }

    if (isset($rawResponse->object)) {
      $isoParsed = $isoFactory->parse($rawResponse->object->iso8583Message);
      $transaction->status = 4;
      $transaction->result_code = $isoParsed[39];
      $transaction->result = $transaction->result_code == '00' ? 1 : 0;
      $transaction->balance = isset($isoParsed[54]) ? $this->fetchRblAmount($isoParsed[54]) : 0;
      $transaction->rrn = isset($isoParsed[37]) ? $isoParsed[37] : ''; // @TODO: Change to null in db and here
      $transaction->uidai_auth_code = isset($isoParsed[48]) ? $isoParsed[48] : '';
      echo $transaction->result;

      if ($transaction->result == 1) {
        $vendor = Vendor::where('csr_id', $payload->csr_id)->lockForUpdate()->first();
        $vendor->balance = $this->updateWallet($vendor, $transaction);
        $vendor->save();
      }
    }

    $transaction->save();
  }

  private function deposit ($payload)
  {
    echo "in deposit \n";
    $transaction = AepsTransaction::find($payload->transaction_id);
    if ($transaction->status == 3 || $transaction->status == 4) return; // Do not send same transaction twice.
    if (Carbon::parse($transaction->updated_at)->diffInSeconds(Carbon::now('Asia/Kolkata')) > 15) {
      $transaction->status = 4;
      $transaction->result = 0;
      $transaction->remarks = 'Transaction Expired';
      $transaction->save();
      return;
    }
    $transaction->status = 1;

    date_default_timezone_set('Asia/Kolkata');
    $elements = [
    2 => '19'.$payload->bank_iin.'0'.$payload->aadhar_no,
    3 => '210000',
    4 =>  sprintf('%010u', $payload->amount).'00',
    7 => date('mdHis'),
    11 => $payload->stan,
    12 => date('His'),
    13 => date('md'),
    18 => "6012",
    41 => $payload->device_id,
    49 => '356',
    123 => '015210101213146000',
    127 => $this->fetchDE127($payload->fingerprint)
    ];
    $params = [
    'mti' => '0200',
    'elements' => $elements
    ];
    $isoFactory = new ISO8583();
    $isoMessage = $isoFactory->create($params);
    $transaction->status = 2;
    \Log::info('iso8583Message: '.$isoMessage);

    $rawResponse = $this->transact($payload, $isoMessage);
    $transaction->status = 3;
    $transaction->save();
    \Log::info('Bank Response: '.json_encode($rawResponse));

    $vendor = Vendor::where('csr_id', $payload->csr_id)->first();
    $vendor->freshness_factor = $rawResponse->nextFreshnessFactor;
    $vendor->save();

    $transaction->bank_response_code = $rawResponse->responseCode;

    if ($rawResponse->responseCode != '00') {
      $transaction->status = 4;
      $transaction->result = 0;
    }

    if (isset($rawResponse->object)) {
      $isoParsed = $isoFactory->parse($rawResponse->object->iso8583Message);
      $transaction->status = 4;
      $transaction->result_code = $isoParsed[39];
      $transaction->result = $transaction->result_code == '00' ? 1 : 0;
      $transaction->balance = isset($isoParsed[54]) ? $this->fetchRblAmount($isoParsed[54]) : 0;
      $transaction->rrn = isset($isoParsed[37]) ? $isoParsed[37] : ''; // @TODO: Change to null in db and here
      $transaction->uidai_auth_code = isset($isoParsed[48]) ? $isoParsed[48] : '';
      echo $transaction->result;
    }

    if ($transaction->result == 1) {
      $vendor = Vendor::where('csr_id', $payload->csr_id)->lockForUpdate()->first();
      $vendor->balance = $this->updateWallet($vendor, $transaction);
      $vendor->save();
    }

    $transaction->save();
  }

  private function withdraw ($payload)
  {
    echo "in withdraw \n";
    $transaction = AepsTransaction::find($payload->transaction_id);
    if ($transaction->status == 3 || $transaction->status == 4) return; // Do not send same transaction twice.
    if (Carbon::parse($transaction->updated_at)->diffInSeconds(Carbon::now('Asia/Kolkata')) > 15) {
      $transaction->status = 4;
      $transaction->result = 0;
      $transaction->remarks = 'Transaction Expired';
      $transaction->save();
      return;
    }
    $transaction->status = 1;

    date_default_timezone_set('Asia/Kolkata');
    $elements = [
    2 => '19'.$payload->bank_iin.'0'.$payload->aadhar_no,
    3 => '010000',
    4 =>  sprintf('%010u', $payload->amount).'00',
    7 => date('mdHis'),
    11 => $payload->stan,
    12 => date('His'),
    13 => date('md'),
    18 => "6012",
    41 => $payload->device_id,
    49 => '356',
    123 => '015210101213146000',
    127 => $this->fetchDE127($payload->fingerprint)
    ];

    $params = [
    'mti' => '0200',
    'elements' => $elements
    ];

    $isoFactory = new ISO8583();
    $isoMessage = $isoFactory->create($params);
    $transaction->status = 2;
    \Log::info('iso8583Message: '.$isoMessage);

    $rawResponse = $this->transact($payload, $isoMessage);
    $transaction->status = 3;
    $transaction->save();
    \Log::info(json_encode($rawResponse));

    $vendor = Vendor::where('csr_id', $payload->csr_id)->first();
    $vendor->freshness_factor = $rawResponse->nextFreshnessFactor;
    $vendor->save();

    $transaction->bank_response_code = $rawResponse->responseCode;

    if ($rawResponse->responseCode != '00') {
      $transaction->status = 4;
      $transaction->result = 0;
    }

    if (isset($rawResponse->object)) {
      $isoParsed = $isoFactory->parse($rawResponse->object->iso8583Message);
      $transaction->status = 4;
      $transaction->result_code = $isoParsed[39];
      $transaction->result = $transaction->result_code == '00' ? 1 : 0;
      $transaction->balance = isset($isoParsed[54]) ? $this->fetchRblAmount($isoParsed[54]) : 0;
      $transaction->rrn = isset($isoParsed[37]) ? $isoParsed[37] : ''; // @TODO: Change to null in db and here
      $transaction->uidai_auth_code = isset($isoParsed[48]) ? $isoParsed[48] : '';
      echo $transaction->result;
    }

    if ($transaction->result == 1) {
      $vendor = Vendor::where('csr_id', $payload->csr_id)->lockForUpdate()->first();
      $vendor->balance = $this->updateWallet($vendor, $transaction);
      $vendor->save();
    }

    $transaction->save();
  }

  private function fetchDE127 ($fingerprint)
  {
    $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];
    $data = ['isoTemplate' => $fingerprint];
    $body = Unirest\Request\Body::json($data);

    $response = Unirest\Request::post('http://localhost:3000/auth', $headers, $body);

    if ($response->code >= 400) {
      Log::info($response->code.' '.json_encode($response->body));
      return Response::json(['message' => 'Service failure. Please check back in a while.'], 500);
    }

    $bitmap127 = '00'.''.(strlen($response->body)+25).'00000400800000000'.''.strlen($response->body);

    $ext_transaction_type = '9117';

    return $bitmap127.$response->body.$ext_transaction_type;
  }

  private function transact ($payload, $iso)
  {
    $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];
    $data = [
      'terminalId' => $payload->terminal_id,
      'freshnessFactor' => $payload->freshness_factor,
      'transType' => '133',
      'csrId' => $payload->csr_id,
      'requestId' => $payload->transaction_id,
      'resentCount' => '1',
      'deviceId' => $payload->device_id,
      'txnTime' => date("M j, Y G:i:s A"),
      'object' => [
        'isVoidTxn' => 'false',
        'iso8583Message' => $iso
      ],
      'version' => '1.2.7.1'
    ];
    $body = Unirest\Request\Body::json($data);
    $aeps_transaction_log = AepsTransactionLog::create(['transaction_id' => $payload->transaction_id, 'request' => json_encode($body)]);
    \Log::info('Request: '.json_encode($body));

    $response = Unirest\Request::post(getenv('RBL_URL'), $headers, $body);

    $aeps_transaction_log->response = json_encode($response->body);
    $aeps_transaction_log->save();

    if ($response->code >= 400) {
      Log::info($response->code.' '.json_encode($response->body));
      // return Response::json(['message' => 'Service failure. Please check back in a while.'], 500);
    }

    return $response->body;
  }

  private function fetchRblAmount ($amount)
  {
    return ltrim(substr($amount, 8, 10), '0').".".substr($amount, 18, 2);
  }

  private function updateWallet ($vendor, $transaction) {
    // $master = CommissionMaster::where('user_id', $vendor->user_id)->where('min' <= $transaction->amount)->where('max' >= $transaction->amount)->first();
    $commission = $this->calculateCommission($transaction->type, $vendor->user_id, $transaction->amount);
    $distCommission = $this->calculateCommission($transaction->type, $vendor->parent_id, $transaction->amount);
    $superDistId = Vendor::where('user_id', $vendor->parent_id)->first()->parent_id;
    $superDistCommission = $this->calculateCommission($transaction->type, $superDistId, $transaction->amount);


    // Perform Wallet Updations for Amount Transacted
    if ($transaction->type == 1 || $transaction->type == 2) {
      $vendor->balance += $transaction->type == 1 ?  -$transaction->amount : ($transaction->type == 2 ? $transaction->amount : 0);
      $amount_wallet_transaction = WalletTransaction::create([
        'user_id' => $vendor->id,
        'transaction_type' => $transaction->type == 1 ? 0 : ($transaction->type == 2 ? 1 : null),
        'amount' => $transaction->amount,
        'balance' => $vendor->balance
      ]);

      $key = $transaction->type == 1 ? 'debit_id' : ($transaction->type == 2 ? 'credit_id' : '');

      AepsWalletAction::create([
        'user_id' => $vendor->id,
        'amount' => $transaction->amount,
        'status' => 1,
        $key => $amount_wallet_transaction->id,
        'transaction_id' => $transaction->id,
        'transaction_type' => $transaction->type,
        'commission' => false
      ]);

      $dipl_vendor = Vendor::where('type', 8)->lockForUpdate()->first();
      $dipl_vendor->balance += $transaction->type == 1 ?  -$transaction->amount : ($transaction->type == 2 ? $transaction->amount : 0);
      $dipl_vendor->save();
      $dipl_amount_wallet_transaction = WalletTransaction::create([
        'user_id' => $dipl_vendor->id,
        'transaction_type' => $transaction->type == 1 ? 0 : ($transaction->type == 2 ? 1 : null),
        'amount' => $transaction->amount,
        'balance' => $dipl_vendor->balance
      ]);

      AepsWalletAction::create([
        'user_id' => $dipl_vendor->id,
        'amount' => $transaction->amount,
        'status' => 1,
        $key => $dipl_amount_wallet_transaction->id,
        'transaction_id' => $transaction->id,
        'transaction_type' => $transaction->type,
        'commission' => false
      ]);

    }

    $dipl_vendor = Vendor::where('type', 8)->lockForUpdate()->first();

    // Perform Wallet Updations for Commission
    if ($commission && $dipl_vendor->commission && $vendor->commission) {
      if ($commission['rate_type'] == 1)
        $vendor->balance += $commission['rate'];
      if ($commission['rate_type'] == 2)
          $vendor->balance += $transaction->amount * $commission['rate']/100;
      $commission_wallet_transaction = WalletTransaction::create([
        'user_id' => $vendor->id,
        'transaction_type' => 1,
        'amount' => $commission['rate'],
        'balance' => $vendor->balance
      ]);

      AepsWalletAction::create([
        'user_id' => $vendor->id,
        'amount' => $commission['rate'],
        'status' => 1,
        'credit_id' => $commission_wallet_transaction->id,
        'transaction_id' => $transaction->id,
        'transaction_type' => $transaction->type,
        'commission' => true
      ]);
    }

    if ($distCommission && $dipl_vendor->commission) {
      $distributor = Vendor::where('user_id', $vendor->parent_id)->lockForUpdate()->first();
      if ($distributor && $distributor->commission == 1) {
        if ($distCommission['rate_type'] == 1)
          $distributor->balance += $distCommission['rate'];
        if ($distCommission['rate_type'] == 2)
          $distributor->balance += $transaction->amount * $distCommission['rate']/100;
        $commission_wallet_transaction = WalletTransaction::create([
          'user_id' => $distributor->id,
          'transaction_type' => 1,
          'amount' => $distCommission['rate'],
          'balance' => $distributor->balance
        ]);

        $distributor->save();

        AepsWalletAction::create([
          'user_id' => $distributor->id,
          'amount' => $distCommission['rate'],
          'status' => 1,
          'credit_id' => $commission_wallet_transaction->id,
          'transaction_id' => $transaction->id,
          'transaction_type' => $transaction->type,
          'commission' => true
        ]);
      } 
    }

    if ($superDistCommission && $dipl_vendor->commission) {
      $superDistributor = Vendor::where('user_id', $superDistId)->lockForUpdate()->first();
      if ($superDistributor && $superDistributor->commission == 1) {
        if ($superDistCommission['rate_type'] == 1)
          $superDistributor->balance += $superDistCommission['rate'];
        if ($superDistCommission['rate_type'] == 2)
          $superDistributor->balance += $transaction->amount * $superDistCommission['rate']/100;
        $commission_wallet_transaction = WalletTransaction::create([
          'user_id' => $superDistributor->id,
          'transaction_type' => 1,
          'amount' => $superDistCommission['rate'],
          'balance' => $superDistributor->balance
        ]);

        $superDistributor->save();

        AepsWalletAction::create([
          'user_id' => $superDistributor->id,
          'amount' => $superDistCommission['rate'],
          'status' => 1,
          'credit_id' => $commission_wallet_transaction->id,
          'transaction_id' => $transaction->id,
          'transaction_type' => $transaction->type,
          'commission' => true
        ]);
      }
      
    }

    return $vendor->balance;
  }

  private function calculateCommission ($type, $userId, $amount)
  {
  	$rate = $this->calculateCommissionRate($type, $amount, $userId);
  	if (! $rate) return false;
  	if ($type == 0) {
  		return ['rate' => $rate->balance_enquiry_rate, 'rate_type' => 1];
  	}
  	return ['rate' => $rate->rate, 'rate_type' => $rate->rate_type];
  }

  private function calculateCommissionRate ($type, $amount, $userId)
  {
    if ($type == 0) {
      $rate = CommissionRate::where('user_id', $userId)->first();
      if (! $rate) return false;
      return $rate;
  	}
    $master = CommissionMaster::where('min', '<=', $amount)->where('max', '>=', $amount)->first();
  	if (! $master) return false;
  	$rate = CommissionRate::where('user_id', $userId)->where('master_id', $master->id)->first();
  	if (! $rate) return false;
  	return $rate;
  }

}

$queue = new Rabbit('aeps_transactions');
$queue->subscribe(new MATM());
