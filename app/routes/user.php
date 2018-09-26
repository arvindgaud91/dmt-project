<?php

Route::get('/admins', 'UsersController@getAdmins');
Route::get('/admins/add', 'UsersController@getAddAdmin');
Route::post('/users', 'UsersController@postAddAdmin');

Route::get('/users', 'UsersController@getUsers');
Route::post('/users/{userId}/update-status', 'UsersController@postUpdateStatus');
Route::post('/users/{userId}/kyc-details', 'UsersController@postKYCDetails');
Route::post('/users/{userId}', 'UsersController@postUpdateUserObj');
Route::get('/users/{userId}', 'UsersController@getUserDetails');

Route::get('/users/{userId}/profile', 'UsersController@getProfile');
Route::get('/users/{userId}/actions/change-password', 'UsersController@getChangePasswordPage');
Route::post('/api/v1/users/{userId}/actions/change-password', 'UsersController@changeDmtUserPassword');

Route::get('/api/v1/users/{id}/actions/incoming-balance-requests', 'WalletsController@getIncomingBalanceRequestsByStatus');
Route::get('/users/actions/csddfflflf-request/vendor/{id}', 'WalletsController@getCreditWalletRequest');
Route::post('/api/v1/users/{id}/actions/credit-request', 'WalletsController@postCreditWalletRequest');

/**
 * Debit vendors Balance - by dist & super-dist
 *
 */
//Route::get('/users/actions/debit-request/vendor/{id}', 'WalletsController@getDebitWalletRequest');
Route::post('/api/v1/users/{id}/actions/debit-wallet', 'UsersController@postDebitWallet');

//SMS 

Route::get('/sms_cron/user_balance/dmt','UsersController@smsUserBalance');
Route::get('/sms_cron/user_closing_balance/dmt','UsersController@smsUserClosingBalance'); 


Route::post('/api/v1/getupdatedbalance', 'UsersController@getUpdatedBalance');

