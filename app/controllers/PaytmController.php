<?php
use Acme\Auth\Auth;
use Acme\SMS\SMS;

/**
*  A controller that deals with remitter APIs
*/
class PaytmController extends BaseController
{
	function __construct()
	{
		
$PAYTM_ENVIRONMENT='TEST'; // PROD
$PAYTM_MERCHANT_KEY='hy#9ggsEuz0KqT5k'; //Change this constant's value with Merchant key downloaded from portal
$PAYTM_MERCHANT_MID='DigInd84512940076362'; //Change this constant's value with MID (Merchant ID) received from Paytm
$PAYTM_MERCHANT_WEBSITE='DigIndWEB';

/*UAT*/
// $PAYTM_MERCHANT_KEY='36x3EfYbYRdf3yv%'; //Change this constant's value with Merchant key downloaded from portal
// $PAYTM_MERCHANT_MID='Digita67225826264126'; //Change this constant's value with MID (Merchant ID) received from Paytm
// $PAYTM_MERCHANT_WEBSITE='WEB_STAGING'; //Change this constant's value with Website name received from Paytm

$PAYTM_DOMAIN = "pguat.paytm.com";
if ($PAYTM_ENVIRONMENT == 'PROD') {
	$PAYTM_DOMAIN = 'secure.paytm.in';
}

$PAYTM_REFUND_URL='https://'.$PAYTM_DOMAIN.'/oltp/HANDLER_INTERNAL/REFUND';
$PAYTM_STATUS_QUERY_URL='https://'.$PAYTM_DOMAIN.'/oltp/HANDLER_INTERNAL/TXNSTATUS';
$PAYTM_STATUS_QUERY_NEW_URL='https://'.$PAYTM_DOMAIN.'/oltp/HANDLER_INTERNAL/getTxnStatus';
$PAYTM_TXN_URL='https://'.$PAYTM_DOMAIN.'/oltp-web/processTransaction';
	}


 

    
function encrypt_e($input, $ky) {
	$key = $ky;
	$size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
	$input = $this->pkcs5_pad_e($input, $size);
	$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
	$iv = "@@@@&&&&####$$$$";
	mcrypt_generic_init($td, $key, $iv);
	$data = mcrypt_generic($td, $input);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);
	$data = base64_encode($data);
	return $data;
}

function decrypt_e($crypt, $ky) {

	$crypt = base64_decode($crypt);
	$key = $ky;
	$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
	$iv = "@@@@&&&&####$$$$";
	mcrypt_generic_init($td, $key, $iv);
	$decrypted_data = mdecrypt_generic($td, $crypt);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);
	$decrypted_data = pkcs5_unpad_e($decrypted_data);
	$decrypted_data = rtrim($decrypted_data);
	return $decrypted_data;
}

function pkcs5_pad_e($text, $blocksize) {
	$pad = $blocksize - (strlen($text) % $blocksize);
	return $text . str_repeat(chr($pad), $pad);
}

function pkcs5_unpad_e($text) {
	$pad = ord($text{strlen($text) - 1});
	if ($pad > strlen($text))
		return false;
	return substr($text, 0, -1 * $pad);
}

function generateSalt_e($length) {
	$random = "";
	srand((double) microtime() * 1000000);

	$data = "AbcDE123IJKLMN67QRSTUVWXYZ";
	$data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
	$data .= "0FGH45OP89";

	for ($i = 0; $i < $length; $i++) {
		$random .= substr($data, (rand() % (strlen($data))), 1);
	}

	return $random;
}

function checkString_e($value) {
	if ($value == 'null')
		$value = '';
	return $value;
}

function getChecksumFromArray($arrayList, $key, $sort=1) {
	if ($sort != 0) {
		ksort($arrayList);
	}
	$str = $this->getArray2Str($arrayList);
	$salt = $this->generateSalt_e(4);
	$finalString = $str . "|" . $salt;
	$hash = hash("sha256", $finalString);
	$hashString = $hash . $salt;
	$checksum = $this->encrypt_e($hashString, $key);
	return $checksum;
}
function getChecksumFromString($str, $key) {
	
	$salt = $this->generateSalt_e(4);
	$finalString = $str . "|" . $salt;
	$hash = hash("sha256", $finalString);
	$hashString = $hash . $salt;
	$checksum = $this->encrypt_e($hashString, $key);
	return $checksum;
}

function verifychecksum_e($arrayList, $key, $checksumvalue) {
	$arrayList = $this->removeCheckSumParam($arrayList);
	ksort($arrayList);
	$str = $this->getArray2StrForVerify($arrayList);
	$paytm_hash = $this->decrypt_e($checksumvalue, $key);
	$salt = substr($paytm_hash, -4);

	$finalString = $str . "|" . $salt;

	$website_hash = hash("sha256", $finalString);
	$website_hash .= $salt;

	$validFlag = "FALSE";
	if ($website_hash == $paytm_hash) {
		$validFlag = "TRUE";
	} else {
		$validFlag = "FALSE";
	}
	return $validFlag;
}

function verifychecksum_eFromStr($str, $key, $checksumvalue) {
	$paytm_hash = $this->decrypt_e($checksumvalue, $key);
	$salt = substr($paytm_hash, -4);

	$finalString = $str . "|" . $salt;

	$website_hash = hash("sha256", $finalString);
	$website_hash .= $salt;

	$validFlag = "FALSE";
	if ($website_hash == $paytm_hash) {
		$validFlag = "TRUE";
	} else {
		$validFlag = "FALSE";
	}
	return $validFlag;
}

function getArray2Str($arrayList) {
	$findme   = 'REFUND';
	$findmepipe = '|';
	$paramStr = "";
	$flag = 1;	
	foreach ($arrayList as $key => $value) {
		$pos = strpos($value, $findme);
		$pospipe = strpos($value, $findmepipe);
		if ($pos !== false || $pospipe !== false) 
		{
			continue;
		}
		
		if ($flag) {
			$paramStr .= $this->checkString_e($value);
			$flag = 0;
		} else {
			$paramStr .= "|" . $this->checkString_e($value);
		}
	}
	return $paramStr;
}

function getArray2StrForVerify($arrayList) {
	$paramStr = "";
	$flag = 1;
	foreach ($arrayList as $key => $value) {
		if ($flag) {
			$paramStr .= $this->checkString_e($value);
			$flag = 0;
		} else {
			$paramStr .= "|" . $this->checkString_e($value);
		}
	}
	return $paramStr;
}

function redirect2PG($paramList, $key) {
	$hashString = $this->getchecksumFromArray($paramList);
	$checksum = $this->encrypt_e($hashString, $key);
}

function removeCheckSumParam($arrayList) {
	if (isset($arrayList["CHECKSUMHASH"])) {
		unset($arrayList["CHECKSUMHASH"]);
	}
	return $arrayList;
}

function getTxnStatus($requestParamList) {
	return $this->callAPI($PAYTM_STATUS_QUERY_URL, $requestParamList);
}

function getTxnStatusNew($requestParamList) {
	$PAYTM_ENVIRONMENT='TEST';
	$PAYTM_DOMAIN = "pguat.paytm.com";
if ($PAYTM_ENVIRONMENT == 'PROD') {
	$PAYTM_DOMAIN = 'secure.paytm.in';
}
	$PAYTM_STATUS_QUERY_NEW_URL='https://'.$PAYTM_DOMAIN.'/oltp/HANDLER_INTERNAL/getTxnStatus';
	return $this->callNewAPI($PAYTM_STATUS_QUERY_NEW_URL, $requestParamList);
}

function initiateTxnRefund($requestParamList) {
	$CHECKSUM = $this->getRefundChecksumFromArray($requestParamList,$PAYTM_MERCHANT_KEY,0);
	$requestParamList["CHECKSUM"] = $CHECKSUM;
	return $this->callAPI($PAYTM_REFUND_URL, $requestParamList);
}

function callAPI($apiURL, $requestParamList) {
	$jsonResponse = "";
	$responseParamList = array();
	$JsonData =json_encode($requestParamList);
	$postData = 'JsonData='.urlencode($JsonData);
	$ch = curl_init($apiURL);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                         
	'Content-Type: application/json', 
	'Content-Length: ' . strlen($postData))                                                                       
	);  
	$jsonResponse = curl_exec($ch);   
	$responseParamList = json_decode($jsonResponse,true);
	return $responseParamList;
}

function callNewAPI($apiURL, $requestParamList) {
	$jsonResponse = "";
	$responseParamList = array();
	$JsonData =json_encode($requestParamList);
	$postData = 'JsonData='.urlencode($JsonData);
	$ch = curl_init($apiURL);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                         
	'Content-Type: application/json', 
	'Content-Length: ' . strlen($postData))                                                                       
	);  
	$jsonResponse = curl_exec($ch);   
	$responseParamList = json_decode($jsonResponse,true);
	return $responseParamList;
}
function getRefundChecksumFromArray($arrayList, $key, $sort=1) {
	if ($sort != 0) {
		ksort($arrayList);
	}
	$str = $this->getRefundArray2Str($arrayList);
	$salt = $this->generateSalt_e(4);
	$finalString = $str . "|" . $salt;
	$hash = hash("sha256", $finalString);
	$hashString = $hash . $salt;
	$checksum = $this->encrypt_e($hashString, $key);
	return $checksum;
}
function getRefundArray2Str($arrayList) {	
	$findmepipe = '|';
	$paramStr = "";
	$flag = 1;	
	foreach ($arrayList as $key => $value) {		
		$pospipe = strpos($value, $findmepipe);
		if ($pospipe !== false) 
		{
			continue;
		}
		
		if ($flag) {
			$paramStr .= $this->checkString_e($value);
			$flag = 0;
		} else {
			$paramStr .= "|" . $this->checkString_e($value);
		}
	}
	return $paramStr;
}
function callRefundAPI($refundApiURL, $requestParamList) {
	$jsonResponse = "";
	$responseParamList = array();
	$JsonData =json_encode($requestParamList);
	$postData = 'JsonData='.urlencode($JsonData);
	$ch = curl_init($refundApiURL);	
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
	//curl_setopt($ch, CURLOPT_URL, $refundApiURL);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	$headers = array();
	$headers[] = 'Content-Type: application/json';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);  
	$jsonResponse = curl_exec($ch);   
	$responseParamList = json_decode($jsonResponse,true);
	return $responseParamList;
}

public function postrefundapi($ORDERID)
{
    
  //  if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
  //   $user = Auth::user();
  //   if ($user->type != 4 || ! in_array('dmt', $user->permissions)) 
  //    return Response::json(['message' => 'Unauthorized access'], 401);

  // $validator = Validator::make(Input::all(), [
  //     'TXNID' => 'required',
  //     'ORDERID' => 'required',
  //     'REFUNDAMOUNT' =>'required',
  //     'TXNTYPE' =>'required',
  //     'REFID'=>'required'
      
  //   ]);
  //  if ($validator->fails())
  //     return Response::json($validator->messages(), 422);
  $PAYTM_MERCHANT_MID='Digita67225826264126'; 
  $PAYTM_MERCHANT_KEY='36x3EfYbYRdf3yv%';

//$paramList["MID"] = $PAYTM_MERCHANT_MID;
///$paramList["TXNID"] = Input::get('TXNID');
//$paramList["ORDERID"] = $ORDERID;
//$paramList["REFUNDAMOUNT"] = Input::get('REFUNDAMOUNT');
//$paramList["TXNTYPE"] = Input::get('TXNTYPE');
//$paramList["REFID"] = Input::get('REFID');
//$paramList['CHECKSUM'] = $this->getChecksumFromArray($paramList,$PAYTM_MERCHANT_KEY);
//$refundApiURL="https://pguat.paytm.com/oltp/H ANDLER_INTERNAL/getTxnStatus";

// Create an array having all required parameters for status query.
		$requestParamList = array("MID" => $PAYTM_MERCHANT_MID , "ORDERID" => $ORDERID);  
		
		$StatusCheckSum = $this->getChecksumFromArray($requestParamList,$PAYTM_MERCHANT_KEY);
		dd($StatusCheckSum);
		$requestParamList['CHECKSUMHASH'] = $StatusCheckSum;

		// Call the PG's getTxnStatusNew() function for verifying the transaction status.
		$responseParamList = $this->getTxnStatusNew($requestParamList);
dd($responseParamList);

// $data=$this->callRefundAPI($refundApiURL,$paramList);
// dd($data);
}




public function postpaytm()
{
   if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
    $user = Auth::user();
    if ($user->type != 4 || ! in_array('dmt', $user->permissions)) 
     return Response::json(['message' => 'Unauthorized access'], 401);
   $validator = Validator::make(Input::all(), [
    'TXN_AMOUNT'=>'required'
    ]);
    if ($validator->fails())
      return Response::json($validator->messages(), 422);

    $transaction_group_id = mt_rand(1000000000, 9999999999);
      $transaction = DmtTransaction::create([
        'user_id' => Auth::user()->id,
        'remitter_id' => Input::get('remitter_id'),
        'beneficiary_id' => Input::get('beneficiary_id'),
        'amount' => Input::get('TXN_AMOUNT'),
        'type' => 2,
        'reference_number' => $user->dmt_vendor->bc_agent.time(),
        'status' => 0,
        'transaction_group_id' => $transaction_group_id
      ]);

  $checkSum = "";
$paramList = array();

$PAYTM_MERCHANT_KEY='36x3EfYbYRdf3yv%'; //UAT
//$PAYTM_MERCHANT_KEY='&NnxmBsZhj&DjBpE'; //LIVE

$PAYTM_MERCHANT_MID='Digita67225826264126'; //UAT
//$PAYTM_MERCHANT_MID='Dig7TL70853042840338'; //LIVE

$PAYTM_MERCHANT_WEBSITE='WEB_STAGING'; //UAT
//$PAYTM_MERCHANT_WEBSITE='Dig7TLWEB'; //LIVE

// Create an array having all required parameters for creating checksum.
$paramList["MID"] = $PAYTM_MERCHANT_MID;
$paramList["ORDER_ID"] = $transaction->id;
$paramList["CUST_ID"] = $transaction->user_id;
$paramList["INDUSTRY_TYPE_ID"] = 'Retail';
$paramList["CHANNEL_ID"] = 'WEB';
$paramList["TXN_AMOUNT"] = Input::get('TXN_AMOUNT');
$paramList["WEBSITE"] = $PAYTM_MERCHANT_WEBSITE;
$paramList["CALLBACK_URL"] = 'http://43.224.136.144:7000/payment/callback';
$paramList['checkSum'] = $this->getChecksumFromArray($paramList,$PAYTM_MERCHANT_KEY);

 return View::make('sender.paytmredirect')->with($paramList); 



}


public function postresponsepaytm()
{
	
$data['transactions']=Input::all();

$txid=Paytm::where('txid',Input::get('TXNID'))->first();
	//dd($txid);
  if($txid)  return Redirect::to('/wallets/balance-request/from-paytm');


$request="{"."amount:".Input::get('TXNAMOUNT')."; user_id:". Auth::user()->id."}";
$Paytm = Paytm::create(['user_id' =>  Auth::user()->id,'txid'=>Input::get('TXNID'),'request'=>$request,'response'=>json_encode($data['transactions'])]);


if(Input::get('RESPCODE') == 01)
{
	




$agent = DmtVendor::where('user_id', Auth::user()->id)->first();
$agent->balance+=Input::get('TXNAMOUNT');
$agent->save();

 $credit = WalletTransaction::create(['user_id' =>  $agent->user_id, 'transaction_type' => 1,'activity'=>'Credit-Request','narration'=>'Paytm TXNID -'.Input::get('TXNID') ,'amount' => Input::get('TXNAMOUNT'), 'balance' => $agent->balance]);

 
    WalletAction::create([
      'user_id' => $agent->user_id,
      'counterpart_id' =>0,
      'amount' => Input::get('TXNAMOUNT'),
      'status' => 1,
      'credit_id' => $credit->id,
      'wallet_request_id' => Input::get('ORDERID'),
      'type' => 0,
      'admin' => false,
      'automatic' => false
    ]);

    
$value=Input::get('TXNAMOUNT');

if((00 <= $value) && ($value <= 50000))
{

//paytm charges

$agent = DmtVendor::where('user_id', Auth::user()->id)->first();
$agent->balance-=15;
$agent->save();

 $debit= WalletTransaction::create(['user_id' =>  $agent->user_id, 'transaction_type' => 0,'activity'=>'Debit','narration'=>'Paytm Charges' ,'amount' => 15, 'balance' => $agent->balance]);

 
    WalletAction::create([
      'user_id' => $agent->user_id,
      'counterpart_id' =>0,
      'amount' => 15,
      'status' => 1,
      'debit_id' => $debit->id,
      'wallet_request_id' => Input::get('ORDERID'),
      'type' => 0,
      'admin' => false,
      'automatic' => false
    ]);



//admin charges 
    
$value=Input::get('TXNAMOUNT');

 $commission = $value*(0.22/100);

$agent = DmtVendor::where('user_id', Auth::user()->id)->first();

$agent->balance-=$commission;
$agent->save();

 $debit= WalletTransaction::create(['user_id' =>  $agent->user_id, 'transaction_type' => 0,'activity'=>'Debit','narration'=>'Paytm Charges' ,'amount' =>  $commission, 'balance' => $agent->balance]);

 
    WalletAction::create([
      'user_id' => $agent->user_id,
      'counterpart_id' =>0,
      'amount' =>  $commission,
      'status' => 1,
      'debit_id' => $debit->id,
      'wallet_request_id' => Input::get('ORDERID'),
      'type' => 0,
      'admin' => false,
      'automatic' => false
    ]);



//admin credit 15 rs  


 $dipl_vendor = DmtVendor::where('type', 8)->lockForUpdate()->first(); //@TODO: provide for multiple dipl_vendors
    $dipl_vendor->balance += 15;
    $dipl_vendor->save();

    $dipl_commissionCreditTx = WalletTransaction::create(['user_id' => $dipl_vendor->user_id, 'transaction_type' => 1, 'amount' => 15, 'balance' => $dipl_vendor->balance,
    	'activity' => 'Credit','narration'=>'Paytm commission orderid :-'.Input::get('ORDERID')]);

    $walletAction = WalletAction::create([
      'user_id' => $dipl_vendor->user_id,
      'counterpart_id' => $agent->user_id,
      'amount' => 15,
      'remarks' => 'Paytm commission orderid :-'.Input::get('ORDERID'),
      'status' => 1,
      'credit_id' => $dipl_commissionCreditTx->id,
      'type' => 0,
      'admin' => true,
      'automatic' => false,
      'commission' => true
      
    ]);

//admin credit commission 


 $dipl_vendor = DmtVendor::where('type', 8)->lockForUpdate()->first(); //@TODO: provide for multiple dipl_vendors
    $dipl_vendor->balance += $commission;
    $dipl_vendor->save();

    $dipl_commissionCreditTx = WalletTransaction::create(['user_id' => $dipl_vendor->user_id, 'transaction_type' => 1, 'amount' => $commission, 'balance' => $dipl_vendor->balance,
    	'activity' => 'Credit','narration'=>'Paytm commission orderid :-'.Input::get('ORDERID')]);

    $walletAction = WalletAction::create([
      'user_id' => $dipl_vendor->user_id,
      'counterpart_id' => $agent->user_id,
      'amount' => $commission,
      'remarks' => 'Paytm commission orderid :-'.Input::get('ORDERID'),
      'status' => 1,
      'credit_id' => $dipl_commissionCreditTx->id,
      'type' => 0,
      'admin' => true,
      'automatic' => false,
      'commission' => true
      
    ]);


}elseif ((50001 <= $value) && ($value <= 100000)) 
{

	//paytm charges

$agent = DmtVendor::where('user_id', Auth::user()->id)->first();
$agent->balance-=10;
$agent->save();

 $debit= WalletTransaction::create(['user_id' =>  $agent->user_id, 'transaction_type' => 0,'activity'=>'Debit','narration'=>'Paytm Charges' ,'amount' => 10, 'balance' => $agent->balance]);

 
    WalletAction::create([
      'user_id' => $agent->user_id,
      'counterpart_id' =>0,
      'amount' => 10,
      'status' => 1,
      'debit_id' => $debit->id,
      'wallet_request_id' => Input::get('ORDERID'),
      'type' => 0,
      'admin' => false,
      'automatic' => false
    ]); 



//admin charges
    
$value=Input::get('TXNAMOUNT');

 $commission = $value*(0.22/100);

$agent = DmtVendor::where('user_id', Auth::user()->id)->first();

$agent->balance-=$commission;
$agent->save();

 $debit= WalletTransaction::create(['user_id' =>  $agent->user_id, 'transaction_type' => 0,'activity'=>'Debit','narration'=>'Paytm Charges' ,'amount' =>  $commission, 'balance' => $agent->balance]);

 
    WalletAction::create([
      'user_id' => $agent->user_id,
      'counterpart_id' =>0,
      'amount' =>  $commission,
      'status' => 1,
      'debit_id' => $debit->id,
      'wallet_request_id' => Input::get('ORDERID'),
      'type' => 0,
      'admin' => false,
      'automatic' => false
    ]);


//admin credit 15 rs  


 $dipl_vendor = DmtVendor::where('type', 8)->lockForUpdate()->first(); //@TODO: provide for multiple dipl_vendors
    $dipl_vendor->balance += 10;
    $dipl_vendor->save();

    $dipl_commissionCreditTx = WalletTransaction::create(['user_id' => $dipl_vendor->user_id, 'transaction_type' => 1, 'amount' => 10, 'balance' => $dipl_vendor->balance,
    	'activity' => 'Credit','narration'=>'Paytm commission orderid :-'.Input::get('ORDERID')]);

    $walletAction = WalletAction::create([
      'user_id' => $dipl_vendor->user_id,
      'counterpart_id' => $agent->user_id,
      'amount' => 10,
      'remarks' => 'Paytm commission orderid :-'.Input::get('ORDERID'),
      'status' => 1,
      'credit_id' => $dipl_commissionCreditTx->id,
      'type' => 0,
      'admin' => true,
      'automatic' => false,
      'commission' => true
      
    ]);

//admin credit


 $dipl_vendor = DmtVendor::where('type', 8)->lockForUpdate()->first(); //@TODO: provide for multiple dipl_vendors
    $dipl_vendor->balance += $commission;
    $dipl_vendor->save();

    $dipl_commissionCreditTx = WalletTransaction::create(['user_id' => $dipl_vendor->user_id, 'transaction_type' => 1, 'amount' => $commission, 'balance' => $dipl_vendor->balance,
    	'activity' => 'Credit','narration'=>'Paytm commission orderid :-'.Input::get('ORDERID')]);

    $walletAction = WalletAction::create([
      'user_id' => $dipl_vendor->user_id,
      'counterpart_id' => $agent->user_id,
      'amount' => $commission,
      'remarks' => 'Paytm commission orderid :-'.Input::get('ORDERID'),
      'status' => 1,
      'credit_id' => $dipl_commissionCreditTx->id,
      'type' => 0,
      'admin' => true,
      'automatic' => false,
      'commission' => true
      
    ]);

}else
{

	//paytm charges
$agent = DmtVendor::where('user_id', Auth::user()->id)->first();
$agent->balance-=5;
$agent->save();

 $debit= WalletTransaction::create(['user_id' =>  $agent->user_id, 'transaction_type' => 0,'activity'=>'Debit','narration'=>'Paytm Charges' ,'amount' => 5, 'balance' => $agent->balance]);

 
    WalletAction::create([
      'user_id' => $agent->user_id,
      'counterpart_id' =>0,
      'amount' => 5,
      'status' => 1,
      'debit_id' => $debit->id,
      'wallet_request_id' => Input::get('ORDERID'),
      'type' => 0,
      'admin' => false,
      'automatic' => false
    ]); 



//admin charges
    
$value=Input::get('TXNAMOUNT');

 $commission = $value*(0.22/100);

$agent = DmtVendor::where('user_id', Auth::user()->id)->first();

$agent->balance-=$commission;
$agent->save();

 $debit= WalletTransaction::create(['user_id' =>  $agent->user_id, 'transaction_type' => 0,'activity'=>'Debit','narration'=>'Paytm Charges' ,'amount' =>  $commission, 'balance' => $agent->balance]);

 
    WalletAction::create([
      'user_id' => $agent->user_id,
      'counterpart_id' =>0,
      'amount' =>  $commission,
      'status' => 1,
      'debit_id' => $debit->id,
      'wallet_request_id' => Input::get('ORDERID'),
      'type' => 0,
      'admin' => false,
      'automatic' => false
    ]);



//admin credit 5 rs  


 $dipl_vendor = DmtVendor::where('type', 8)->lockForUpdate()->first(); //@TODO: provide for multiple dipl_vendors
    $dipl_vendor->balance += 5;
    $dipl_vendor->save();

    $dipl_commissionCreditTx = WalletTransaction::create(['user_id' => $dipl_vendor->user_id, 'transaction_type' => 1, 'amount' => 5, 'balance' => $dipl_vendor->balance,
    	'activity' => 'Credit','narration'=>'Paytm commission orderid :-'.Input::get('ORDERID')]);

    $walletAction = WalletAction::create([
      'user_id' => $dipl_vendor->user_id,
      'counterpart_id' => $agent->user_id,
      'amount' => 5,
      'remarks' => 'Paytm commission orderid :-'.Input::get('ORDERID'),
      'status' => 1,
      'credit_id' => $dipl_commissionCreditTx->id,
      'type' => 0,
      'admin' => true,
      'automatic' => false,
      'commission' => true
      
    ]);

//admin credit


 $dipl_vendor = DmtVendor::where('type', 8)->lockForUpdate()->first(); //@TODO: provide for multiple dipl_vendors
    $dipl_vendor->balance += $commission;
    $dipl_vendor->save();

    $dipl_commissionCreditTx = WalletTransaction::create(['user_id' => $dipl_vendor->user_id, 'transaction_type' => 1, 'amount' => $commission, 'balance' => $dipl_vendor->balance,
    	'activity' => 'Credit','narration'=>'Paytm commission orderid :-'.Input::get('ORDERID')]);

    $walletAction = WalletAction::create([
      'user_id' => $dipl_vendor->user_id,
      'counterpart_id' => $agent->user_id,
      'amount' => $commission,
      'remarks' => 'Paytm commission orderid :-'.Input::get('ORDERID'),
      'status' => 1,
      'credit_id' => $dipl_commissionCreditTx->id,
      'type' => 0,
      'admin' => true,
      'automatic' => false,
      'commission' => true
      
    ]);

}


return View::make('sender.paytmresponse')->with($data); 
}else
{
return View::make('sender.paytmresponse')->with($data); 

}
    



} 






public function DistRequestPaytm()
{
   if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
    $user = Auth::user();
    if ($user->type != 4 || ! in_array('dmt', $user->permissions)) 
     return Response::json(['message' => 'Unauthorized access'], 401);

   $validator = Validator::make(Input::all(), [
    'TXN_AMOUNT'=>'required'
    ]);
    if ($validator->fails())

      return Response::json($validator->messages(), 422); 

  if(Input::get('TXN_AMOUNT') > 200000) return Redirect::back()->with('error','Minimum Transaction Amount Should Be Less Than 2,00,000');

  
 $checkSum = "";
$paramList = array();

//$PAYTM_MERCHANT_KEY='36x3EfYbYRdf3yv%'; //UAT
$PAYTM_MERCHANT_KEY='&NnxmBsZhj&DjBpE'; //LIVE

//$PAYTM_MERCHANT_MID='Digita67225826264126'; //UAT
$PAYTM_MERCHANT_MID='Dig7TL70853042840338'; //LIVE

//$PAYTM_MERCHANT_WEBSITE='WEB_STAGING'; //UAT
$PAYTM_MERCHANT_WEBSITE='Dig7TLWEB'; //LIVE

// Create an array having all required parameters for creating checksum.
 $transaction_group_id = mt_rand(1000000000, 9999999999);
$paramList["MID"] = $PAYTM_MERCHANT_MID;
$paramList["ORDER_ID"] = $transaction_group_id;
$paramList["CUST_ID"] = $user->id;
$paramList["INDUSTRY_TYPE_ID"] = 'ECommerce';
$paramList["CHANNEL_ID"] = 'WEB';
$paramList["TXN_AMOUNT"] = Input::get('TXN_AMOUNT');
$paramList["WEBSITE"] = $PAYTM_MERCHANT_WEBSITE;
$paramList["CALLBACK_URL"] = 'http://dmt-test.digitalindiapayments.com:8000/payment/callback';
$paramList['checkSum'] = $this->getChecksumFromArray($paramList,$PAYTM_MERCHANT_KEY);
return View::make('sender.paytmredirect')->with($paramList); 



}


//imps paytm


public function postPaytmImps()
{
   if (! Auth::user()) return Response::json(['message' => 'Unauthorized access.'], 401);
    $user = Auth::user();
    if ($user->type != 4 || ! in_array('dmt', $user->permissions)) 
     return Response::json(['message' => 'Unauthorized access'], 401);
   $validator = Validator::make(Input::all(), [
    'TXN_AMOUNT'=>'required'
    ]);

    if ($validator->fails())
      return Response::json($validator->messages(), 422);


   if(Input::get('TXN_AMOUNT') > 25000) return Redirect::back()->with('error','Minimum Transaction Amount Should Be Less Than 25,000');
   	if (Input::get('TXN_AMOUNT') > $user->dmt_vendor->balance) return Response::json(['message' => 'Insufficient Balance'], 422);

   	
//     $split = ceil(Input::get('TXN_AMOUNT')/5000);
    
//     $transAmount = Input::get('TXN_AMOUNT');

// for ($i=0; $i < $split; $i++) 
// {
     
     // $amount = min(5000, $transAmount);

    $transaction_group_id = mt_rand(1000000000, 9999999999);
      $transaction = DmtTransaction::create([
        'user_id' => Auth::user()->id,
        'remitter_id' => Input::get('remitter_id'),
        'beneficiary_id' => Input::get('beneficiary_id'),
        'amount' => $amount,
        'type' => 2,
        'reference_number' => $user->dmt_vendor->bc_agent.time(),
        'status' => 0,
        'transaction_group_id' => $transaction_group_id
      ]);

  $checkSum = "";
$paramList = array();

//$PAYTM_MERCHANT_KEY='hy#9ggsEuz0KqT5k'; //UAT
$PAYTM_MERCHANT_KEY='&NnxmBsZhj&DjBpE'; //LIVE

//$PAYTM_MERCHANT_MID='DigInd84512940076362'; //UAT
$PAYTM_MERCHANT_MID='Dig7TL70853042840338'; //LIVE

//$PAYTM_MERCHANT_WEBSITE='DigIndWEB'; //UAT
$PAYTM_MERCHANT_WEBSITE='Dig7TLWEB'; //LIVE

// Create an array having all required parameters for creating checksum.
$paramList["BANK_ACC_NO"]= Input::get('BANK_ACC_NO');
$paramList["IFSC_CODE"]= Input::get('IFSC_CODE');
// $paramList["BANK_ACC_NO"]='039305008196';
// $paramList["IFSC_CODE"]= 'ICIC0000393';
$paramList["ACC_TYPE"]= null;
$paramList["MOBILE_NO"] = Input::get('remitter_mobile');
$paramList["SENDER_NAME"] = 'pradip';
$paramList["AMOUNT"] = $amount;
$paramList["CURRENCY"] = 'INR';
$paramList["MID"] = $PAYTM_MERCHANT_MID;
$paramList["ORDER_ID"] = $transaction->id;
$paramList["REQUEST_TYPE"] = 'P2B_S2S';
//$paramList["CHANNEL"] = 'WEB';
$paramList["REMARKS"] = 'EkoIndiaTid519130077';
$paramList['CHECKSUM'] = $this->getChecksumFromArray($paramList,$PAYTM_MERCHANT_KEY);
//dd($paramList);
$headers = [
    'Accept' => 'application/json',
    'Content-Type' => 'application/json'
  ];
  //print_r("https://secure.paytm.in/oltp/P2B?JsonData=".json_encode($paramList)."<br>");
 $response = \Unirest\Request::get("https://secure.paytm.in/oltp/P2B?JsonData=".json_encode($paramList), $headers);
 $data_response=json_decode($response->raw_body);

$paytmlog= new PaytmImpsLog();
$paytmlog->user_id=Auth::user()->id;
$paytmlog->remitter_id=Input::get('remitter_id');
$paytmlog->beneficiary_id=Input::get('beneficiary_id');
$paytmlog->request=json_encode($paramList);
$paytmlog->response=$response->raw_body;
$paytmlog->save();


$PaytmImps=new PaytmImps();
$PaytmImps->user_id=Auth::user()->id;
$PaytmImps->remitter_id=Input::get('remitter_id');
$PaytmImps->beneficiary_id=Input::get('beneficiary_id');
$PaytmImps->order_id=$data_response->ORDER_ID;
$PaytmImps->amount=$amount;
$PaytmImps->status=$data_response->TXN_STATUS;
$PaytmImps->response_code=$data_response->RESPCODE;
$PaytmImps->pg_tx_id=$data_response->PG_TXN_ID;
$PaytmImps->bank_tx_id=$data_response->BANK_TXN_ID;
$PaytmImps->save();

$DmtTransaction=DmtTransaction::where('id',$transaction->id)->first();
$DmtTransaction->reference_number=$data_response->PG_TXN_ID;
$DmtTransaction->status=$data_response->RESPCODE == 01 ? 2 : 1;
$DmtTransaction->bank_transaction_id=$data_response->BANK_TXN_ID;
$DmtTransaction->remarks=$data_response->TXN_STATUS;
$DmtTransaction->result=$data_response->RESPCODE == 01 || $data_response->RESPCODE == 8311 || $data_response->RESPCODE == 400 ? 1 : 0;
$DmtTransaction->save();

//Debit wallet 
$this->walletUpdates(Auth::user()->id, $transaction);


 if($DmtTransaction->result != 1)
 {
 	 $this->reverseWalletUpdates($user->dmt_vendor->id, $transaction);
 }

//   $transAmount -= 5000;
// }

 return Response::json(['id' => $DmtTransaction->transaction_group_id], 200);
 
}

public function getimpsrecepit($id)
{
$data['transactions']=PaytmImps::where('id',$id)->first();


 return View::make('sender.impsrecepit')->with($data); 

}

private function walletUpdates ($dmt_vendor_id, $transaction)
{//dd($dmt_vendor_id);
      $dmt_vendor = DmtVendor::where('user_id',$dmt_vendor_id)->first();
      $dmt_vendor->balance -= $transaction->amount;
      $dmt_vendor->save();

      $remitter=Remitter::where('id','=',$transaction->remitter_id)->first();
      //dd($remitter);
      $remitter_phone=$remitter->phone_no;
      

      $debit_tx = WalletTransaction::create([
          'user_id' => $dmt_vendor->user_id,
          'transaction_type' => 0,
          'activity' =>'Debit',
          'narration' =>'Transfer Paytm:-'.$transaction->reference_number.'/'.$remitter_phone,
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
          'narration' =>'Transfer Paytm:-'.$transaction->reference_number.'/'.$remitter_phone,
          'activity'=>'credit',
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

}   	