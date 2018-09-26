<?php

Route::get('/api/v1/transactions', 'AdminReportsController@getAdminTransactionReport');
Route::get('/api/v1/wallets', 'AdminReportsController@getAdminWalletReport');
Route::get('/api/v1/superdistributorcommissions', 'AdminReportsController@getAdminSuperDistributorCommissionsReport');
Route::get('/api/v1/distributorcommissions', 'AdminReportsController@getAdminDistributorCommissionsReport');
//api for export function
Route::post('/api/v1/wallet-exports','AdminReportsController@getAdminWalletExport');
Route::post('/api/v1/transaction-exports','AdminReportsController@getAdminTransactionExport');
Route::get('/api/v1/commission-exports','AdminReportsController@getAdminDistributorCommissionsExport');
Route::get('/api/v1/commission-superdistributor-exports','AdminReportsController@getAdminSuperDistributorCommissionsExport');

Route::get('/api/v1/bank-details','AdminReportsController@getBankDetails');
Route::post('/api/v1/update-bank-status','AdminReportsController@updateBankStatus');

Route::get('/api/v1/dmt-last-day-closing-reports', 'AdminReportsController@getAdminDMTLastDayclosingReport');
Route::post('/api/v1/dmt-last-day-closing-exports','AdminReportsController@getAdminLastDayClosingExports');

Route::post('/api/v1/get-getsuspiciousagent','AdminReportsController@getSuspiciousAgent');

Route::get('/api/v1/dmt-agent-average-transactions', 'AdminReportsController@getAdminAgentAverageTransactionReports');
Route::post('/api/v1/dmt-agent-average-transactions-exports','AdminReportsController@getAdminAgentAverageTransactionExports');
Route::post('/api/v1/dmt-closing-balance-exports','AdminReportsController@getAdminClosingBalanceExports');