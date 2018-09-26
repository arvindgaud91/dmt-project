<?php

Route::get('/remitter/add/{id}', 'RemitterController@getAddRemitter');
Route::get('/remitter/add', 'RemitterController@getAddRemitterid');
Route::get('/remitters', 'RemitterController@getRemitters');
Route::get('/remitter/{id}', 'RemitterController@getRemitterByIds');
Route::post('/remitterdata', 'RemitterController@getRemitterById');
Route::get('/remitter/{id}/beneficiary/add', 'RemitterController@getAddBeneficiary');
Route::get('/remitter/{id}/beneficiary/delete_otp', 'RemitterController@getotpdeleteBeneficiary');
Route::get('/remitter/{id}/{ids}/beneficiary/beneficiary_otp', 'RemitterController@getotpBeneficiary');

Route::get('/remitter/{id}/beneficiary/validation', 'RemitterController@getvalidationBeneficiary');




Route::post('/remitter', 'RemitterController@postAddRemitter');

Route::post('/beneficiary/webadd', 'RemitterController@postAddBeneficiary');
Route::post('/remitter/{id}/deleted_otpbeneficiary', 'RemitterController@otpremitter');
Route::post('/remitter/{id}/{ids}/{idss}/otpbeneficiary', 'RemitterController@otpbeneficiary');

Route::post('/validationbeneficiary', 'RemitterController@checkvalidation');



Route::get('/refundReport', 'RemitterController@refundReport');
Route::post('/getOtpRefund', 'RemitterController@getOtpRefund');
Route::post('/getRefundtransaction', 'RemitterController@getRefundtransaction');

Route::get('/remitter/{id}/beneficiary/beneficiary_otpresend_ben_otp', 'RemitterController@resendotp');

Route::get('/remitter/{id}/beneficiary/beneficiary_otpresend_ben_otp_link', 'RemitterController@resendotp_ben');

Route::get('/remitter/{id}/beneficiary/beneficiary_otpresend', 'RemitterController@resendotp_benvalidation');




Route::post('/api/v1/actions/search/remitter', 'RemitterController@postSearchRemitter');
Route::get('/api/v1/actions/search/remitter', 'RemitterController@postSearchRemitter');


Route::post('get/api/v1/actions/search/remitter/{data}', function () {
	return View::make('sender.receiver-detailsq');
});


Route::get('/api/v1/bank/actions/', 'RemitterController@getAddBankBranchByIfsc');
Route::get('/api/v1/bank/actions/ifsc/', 'RemitterController@getBankBranchByIfsc');

Route::get('/api/v1/actions/lookup/pincode', 'RemitterController@getAddressInfoByPincode');



Route::get('/requery/{txId}','RemitterController@requery');

Route::delete('/api/v1/beneficiary/{id}', 'RemitterController@deleteBeneficiary');
Route::post('/api/v1/beneficiary-delete-resendotp/{id}', 'RemitterController@deleteBeneficiaryResendOTP');

Route::get('/api/v1/bank/bankId','RemitterController@getIfscByBank');

Route::get('transactt','TransactionsController@transactt');
Route::post('/deleteBeneficiaryweb','RemitterController@deleteBeneficiaryweb');



Route::post('pincode','RemitterController@pincode');
Route::post('/gstRequest', 'RemitterController@postAddGST');

?>