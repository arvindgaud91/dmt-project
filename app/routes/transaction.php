<?php

// Dummy route
Route::get('/transactions/transact', 'TransactionsController@getTransactionForm');
Route::get('/transactions/{id}/actions/receipt', 'TransactionsController@getTransactionReceipt');

Route::post('/api/v1/transactions/balance-enquiry', 'TransactionsController@postBalanceEnquiry');
Route::post('/api/v1/transactions/deposit', 'TransactionsController@postDeposit');
Route::post('/api/v1/transactions/withdraw', 'TransactionsController@postWithdraw');

Route::get('/api/v1/transactions/{id}/actions/status', 'TransactionsController@getPollTransaction');

// DMT Routes
Route::post('/transactions/imps/beneficiary', 'TransactionsController@getImpsForm');
Route::post('/transactions/neft/beneficiary', 'TransactionsController@getNeftForm');
Route::get('/transactions/paytm/beneficiary/{id}', 'TransactionsController@getpaytmForm');


Route::post('/api/v1/transactions/neft', 'TransactionsController@postNeft');
Route::post('/api/v1/transactions/imps', 'TransactionsController@postImps');
Route::post('/api/v1/transactions/paytm', 'PaytmController@postpaytm');
Route::post('/api/v1/transaction/paytm', 'PaytmController@DistRequestPaytm');



Route::post('/payment/callback', 'PaytmController@postresponsepaytm');
Route::get('/payment/refund/{id}', 'PaytmController@postrefundapi');

Route::post('/api/v1/transactions/pimps', 'PaytmController@postPaytmImps');
Route::get('/paytm/imps/{id}', 'PaytmController@getimpsrecepit');
Route::get('/transactions/pimps/beneficiary/{id}', 'TransactionsController@getpaytmImpsForm');

//Added on 12/8/2017

Route::get('/transaction-reports','TransactionsController@getTransactionReport');
Route::post('/transaction-reportsdatewise','TransactionsController@getTransactionReportdatewise');
Route::get('/transaction-reportsdatewise','TransactionsController@getTransactionReport');

Route::get('/transaction-reportsdatewise/{id}/{idd}/{iddd}','TransactionsController@getTransactionReportpage');



Route::post('/transaction-reportsdatewiseexport','TransactionsController@getTransactionReportexport');

//Added on 17/8/2017

Route::get('/receipts/{txId}','TransactionsController@getDMTTransactionReceipt');

Route::get('transactions/imps/beneficiary/transactions/imps/beneficiary/{id}/receipts', 'TransactionsController@getReceipt');
Route::get('transactions/neft/beneficiary/transactions/neft/beneficiary/{id}/receipts', 'TransactionsController@getReceipt');

//export functionality
Route::post('/export-transactions-report', 'TransactionsController@getTransactionExport');


Route::get('/wallets/balance-request/from-paytm', function () {
	return View::make('wallets.paytm-distributor');
});

Route::post('/api/v1/transactions/paytm', 'PaytmController@postpaytm');
Route::post('/api/v1/transaction/paytm', 'PaytmController@DistRequestPaytm');



Route::post('/payment/callback', 'PaytmController@postresponsepaytm');
Route::get('/payment/refund/{id}', 'PaytmController@postrefundapi');
