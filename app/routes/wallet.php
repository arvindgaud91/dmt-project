<?php

Route::get('/wallets/balance-request', 'WalletsController@getBalanceRequest');
Route::get('/wallets/balance-request-TEST', 'WalletsController@getBalanceRequestTEST');




Route::get('/wallets/balance-request/from-distributor', 'WalletsController@getBalanceRequestFromDistributor');
Route::get('/wallets/balance-request/from-super-distributor', 'WalletsController@getBalanceRequestFromSuperDistributor');

Route::get('/wallets/balance-request/incoming/vendor/{id}', 'WalletsController@getIncomingBalanceRequestByParentVendor');

Route::post('/api/v1/wallets/balance-requests/', 'WalletsController@postBalanceRequest');
Route::post('/api/v1/wallets/balance-requests/from-distributors', 'WalletsController@postBalanceRequestFromDistributors');
Route::post('/api/v1/wallets/balance-requests/from-super-distributors', 'WalletsController@postBalanceRequestFromSuperDistributors');

Route::post('/api/v1/wallets/balance-requests/{id}/from-super-distributors/actions/approve', 'WalletsController@postApproveBalanceRequestBySuperDistributor');
Route::post('/api/v1/wallets/balance-requests/{id}/from-distributors/actions/approve', 'WalletsController@postApproveBalanceRequestByDistributor');

Route::post('/api/v1/wallets/balance-requests/{id}/from-super-distributors/actions/reject', 'WalletsController@postRejectBalanceRequestBySuperDistributor');
Route::post('/api/v1/wallets/balance-requests/{id}/from-distributors/actions/reject', 'WalletsController@postRejectBalanceRequestByDistributor');


//Added on 11/8/2017
Route::get('/wallet-reports','WalletsController@getWalletReport');
Route::post('/wallet-reportsdaywise','WalletsController@getWalletReportdaywise');

Route::post('/wallet-reportsdaywiseexport','WalletsController@getWalletReportdaywiseexport');

Route::get('/wallet-reportsdaywise','WalletsController@getWalletReport');


Route::post('/api/auth/v1/wallets/balance-requests/{id}/approve', 'WalletsController@postApproveBalanceRequestByAdmin');
Route::post('/api/auth/v1/wallets/balance-requests/{id}/reject', 'WalletsController@postRejectBalanceRequestByAdmin');

Route::get('/api/v1/wallets/balance-requests/{status}', 'WalletsController@getBalanceRequests');

Route::get('/distributor-wallet-reports','WalletsController@getDistributorWalletReport');

//export functionality

Route::post('/export-wallets-report', 'WalletsController@getWalletExport');
Route::post('/export-distributor-wallets-report','WalletsController@getDistributorWalletExport');

//for export
Route::get('/api/v1/wallets/export-balance-requests/{status}', 'WalletsController@exportBalanceRequests');



Route::get('/wallets/balance-request/from-paytm', function () {
	return View::make('wallets.paytm-distributor');
});

