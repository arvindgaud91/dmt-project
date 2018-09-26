<?php

use Acme\Auth\Auth;
use Acme\Helper\Rabbit;
use Acme\Helper\GateKeeper;
use Acme\ISO8583\ISO8583;
use Carbon\Carbon;

// DMT Routing starts
Route::get('/test-login', 'SessionsController@loginRBL');
Route::get('/receiver-details',function(){
	return View::make('sender.receiver-details');
});

Route::get('/add-sender', function () {
	return View::make('sender.addSender');
});
Route::get('/search-sender', function () {
	return View::make('sender.search');
});
Route::get('/sender-details', function () {
	return View::make('sender.details');
});
Route::get('/add-receiver', function () {
	return View::make('sender.addReceiver');
});
Route::get('/refund', function () {
	return View::make('sender.refund');
});
Route::get('/profile', function () {
	return View::make('users.profile');
});
Route::get('/change-password', function () {
	return View::make('users.change-password');
});
Route::get('/wallet-request-distributor', function () {
	return View::make('wallets.from-distributor');
});
Route::get('/wallet-request-dip', function () {
	return View::make('wallets.balance-request');
});
Route::get('/commission-reports',function(){
	return View::make('reports.commission-reports');
});


Route::get('/balance-transfer','UsersController@getAgentBalance');

Route::post('/balance-update/{user_name}/{id}', 'UsersController@postAgentBalance');


Route::get('/imps',function(){
	return View::make('sender.imps');
});
Route::get('/neft',function(){
	return View::make('sender.neft');
});
Route::get('/incoming-request',function(){
	return View::make('wallets.incoming-request');
});

Route::get('/distributor-dashboard',function(){
	return View::make('home.index-distributor');
});

// Route::get('/request-reports', function () {
// 	return View::make('reports.request-reports');
// });
Route::get('/receipt', function () {
	return View::make('/receipts./receipt');
});

// DMT Routing ends
Route::get('/withdraw', function () {
	if (! in_array(Input::get('type', ''), ['balance-enquiry', 'deposit', 'withdraw'])) return Redirect::to('/');
	if (Input::get('type') == 'balance-enquiry')
		GateKeeper::checkBlacklist(Auth::user(), 1, 'balance-enquiry');
	if (Input::get('type') == 'deposit')
		GateKeeper::checkBlacklist(Auth::user(), 1, 'deposit');
	if (Input::get('type') == 'withdraw')
		GateKeeper::checkBlacklist(Auth::user(), 1, 'withdraw');

	$data['transactionType'] = Input::get('type');
	return View::make('dummy.test1')->with($data);
});
Route::get('/incoming-request', function () {
	return View::make('wallets.incoming-request');
});

// Route::get('/receipt', function () {
// 	return View::make('receipts.receipt');
// });
Route::post('/api/v1/bank', 'BankController@addBank');

Route::post('/api/v1/bankbranch', 'BankController@bankbranchAdd');
Route::post('/api/v1/addpincode', 'BankController@Addpincode');

Route::post('/api/user/{id}/v1/vendor/credit','VendorsController@creditamount');
Route::post('/api/user/{id}/v1/vendor/debit','VendorsController@debitamount');

// Route::get('/transaction-reports', function () {
// 	return View::make('reports.transaction-reports');
// });
// Route::get('/wallet-reports', function () {
// 	return View::make('reports.wallet-reports');
// });

Route::get('/test1', function ()
{
	var_dump(['code' => 1, 'wer']);

});
Route::get('/support', function ()
{
    
	$data = MasterSupport::lists('support_name');
	return View::make('support.support', ['support_data' => $data]);

});

Route::post('/support','SupportController@add_support');
Route::get('/support-report','SupportController@support_report');
Route::get('test-dmt', 'SupportController@support_all_data');
Route::get('/test2', function () {
	$iso = json_decode(Input::get('iso'));
	// $iso = json_decode('{"object":{"iso8583Message":"0210F23A00100EC184000000000000000002196076460906016781226210000000000020000070511432939466911432907050705C00000000718611394669BL900800RDI001980000000RDI0019803209218f144f334f1eb483031c001c49cd3560200002356D00000000000000007804000400800000001100051218Postilion:MetaData1813RRN11113RRN2127186113946699117","isVoidTxn":false},"responseCode":"00","responseMessage":"SUCCESS","requestId":"119874","nextFreshnessFactor":"8069283581739341644"}');
	$isoFactory = new ISO8583();
	dd($isoFactory->parse($iso->object->iso8583Message));
	// dd($isoParsed[1].'\n');
	// {"object":{"iso8583Message":"0210F23800000280800000000000000000221960739309060167812263100000000000000000614121432316807121432061412RDI0000135601521010121314600000002000000000800000009117","isVoidTxn":false},"responseCode":"00","responseMessage":"SUCCESS","requestId":"80","nextFreshnessFactor":"1024771769660633682"}
});

//Added on 27/10/2017

Route::get('/dmt-agent-sales-report','SalesController@getAgentSalesReport');
Route::get('/dmt-distributor-sales-report','SalesController@getDistributorSalesReport');
Route::get('/dmt-distributor-sales-date-report','SalesController@getDistributorSalesDateReport');
Route::get('/dmt-agent-sales-report-for-distributor/{user_id}','SalesController@getAgentSalesReportForDistributor');

Route::get('/agent-distributor-date-report-for-area-sales-officer/{user_id}','SalesController@getAgentSalesDateReportForDistributor');

/*******************Area Sales Officer*******************/
Route::get('/area-sales-officer-report','SalesController@getAreaSalesOfficerReport');

Route::get('/sales-executive-reports-for-area-sales-officer/{user_id}','SalesController@getSalesExecutiveReportForAreaSalesOfficer');

Route::get('/agent-reports-for-area-sales-officer/{user_id}','SalesController@getAgentReportForAreaSalesOfficer');

Route::get('/distributor-area-sales-report','SalesController@getDistributorAreaSalesOfficerReport');

Route::get('/sales-executive-area-sales-officer-date-report','SalesController@getSalesExecutiveSalesDateReport');

Route::get('/distributor-date-report-for-area-sales-officer/{user_id}','SalesController@getDistributorSalesExecutiveSalesDateReport');

/*******************Area Sales Manager*******************/

Route::get('/area-sales-manager-report','SalesController@getAreaSalesManagerReport');

Route::get('/sales-executive-reports-for-area-sales-manager/{user_id}','SalesController@getSalesExecutiveReportForAreaSalesManager');

Route::get('/sales-executive-area-sales-manager-report','SalesController@getSalesExecutiveSalesManagerReport');

Route::get('/area-sales-officer-area-sales-manager-date-report','SalesController@getSalesOfficeAreaSalesManagerSalesDateReport');

Route::get('/sales-executive-date-reports-for-area-sales-manager/{user_id}','SalesController@getSalesExecutiveAreaSalesManagerSalesDateReport');

/*******************Cluster Head*******************/

Route::get('/cluster-head-report','SalesController@getClusterHeadReport');

Route::get('/area-sales-officer-for-clustor-head/{user_id}','SalesController@getAreaSalesOfficerClustorHeadReport');

Route::get('/area-sales-officer-report-for-clustor-head','SalesController@getAreaSalesOfficerForClustorHeadReport');

Route::get('/area-sales-manager-cluster-head-date-report','SalesController@getSalesManagerClusterHeadSalesDateReport');

Route::get('/area-sales-officer-date-reports-for-cluster-head/{user_id}','SalesController@getSalesOfficeClusterHeadDateReport');

/*******************State Head*******************/

Route::get('/state-head-report','SalesController@getStateHeadReport');

Route::get('/area-sales-manager-reports-for-cluster-head/{user_id}','SalesController@getAreaSalesManagerForClusterHeadReport');

Route::get('/area-sales-manager-reports-for-state-head','SalesController@getAreaSalesManagerForStateHeadReport');

Route::get('/cluster-head-state-head-date-report','SalesController@getClusterHeadStateHeadSalesDateReport');

Route::get('/area-sales-manager-date-reports-for-cluster-head/{user_id}','SalesController@getAreaSalesManagerClusterHeadSalesDateReport'); 

/*******************Regional Head*******************/

Route::get('/regional-head-report','SalesController@getRegionalHeadReport');

Route::get('/cluster-head-reports-for-state-head/{user_id}','SalesController@getClusterHeadForStateHeadReport');

Route::get('/cluster-head-reports-for-regional-head','SalesController@getClusterHeadForRegionalHeadReport');

Route::get('/state-head-regional-head-date-report','SalesController@getStateHeadRegionalHeadSalesDateReport');

Route::get('/cluster-head-regional-head-date-report/{user_id}','SalesController@getClusterHeadRegionalHeadSalesDateReport');

/*******************Regional Head End*******************/

Route::get('/export-dmt-agent-sales-report', 'SalesController@getAgentExport');
Route::get('/export-dmt-distributor-sales-report', 'SalesController@getDistributorExport');
Route::get('/export-dmt-distributor-sales-date-report', 'SalesController@getDistributorDateExport');

Route::get('/ticket', function () {
	return View::make('ticket');
});
 

 Route::get('/hub', function () {
	return View::make('hub');
});

Route::get('ticketlogin','TicketController@ticketlogin');

function getBanksDict ($banks)
{
	$banksDict = [];
	foreach ($banks->toArray() as $bank) {
		$banksDict[$bank['id']] = $bank['name'];
	}
	return $banksDict;
}
function getStatus ($status, $result)
{
	if (($status == 3 || $status == 4) && $result == 0) return 'Failed';
	if (($status == 3 || $status == 4) && $result == 1) return 'Success';
	// if (($status == 0 || $status == 1) && Carbon::parse()
	// @todo ensure the status changes to 4 in case of a timeout
	return 'In progress';
}
function calculateCommission ($type, $userId, $amount)
{
	$rate = calculateCommissionRate($amount, $userId);
	if (! $rate) return false;
	if ($type == 0) {
		return $amount * ($rate->balance_enquiry_rate / 100);
	}
	return $amount * ($rate->rate / 100);
}
function calculateCommissionRate ($amount, $userId)
{
	$master = CommissionMaster::where('min', '<=', $amount)->where('max', '>=', $amount)->first();
	if (! $master) return false;
	$rate = CommissionRate::where('user_id', $userId)->where('master_id', $master->id)->first();
	if (! $rate) return false;
	return $rate;
}

foreach (File::allFiles(__DIR__ . '/routes') as $file){
	require $file->getPathname();
}

Route::get('/api/v1/recovery/balance-recovery-dmt','AdminReportsController@getBalanceRecoveryDmt');




Route::get('/webremitter','WebapiController@remitter');

Route::get('/webben','WebapiController@benf');

Route::get('/websearchremitter','WebapiController@searchRemitter');

Route::get('/webdeleteBeneficiary','WebapiController@deleteBeneficiary');

Route::get('/webdashboard','WebapiController@dashboard');

Route::get('/webwalletRequest','WebapiController@walletRequest');

Route::get('/webwalletRequestAdmin','WebapiController@walletRequestAdmin');

Route::get('/requestApprovalDist','WebapiController@requestApprovalDist');

Route::get('/webprofile','WebapiController@profile');

Route::get('/webchangePassword','WebapiController@ChangePassword');

Route::get('/forgotPasswordOTP','WebapiController@forgotPasswordOTP');

Route::get('/forgotPasswordConfirm','WebapiController@forgotPasswordConfirm');