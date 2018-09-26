<?php
Route::get('/getkycdetails','KycController@getkycdetails');
Route::get('/updateuploaded/{kycid}','KycController@updateuploaded');
Route::get('/getRemitterDetails/{rid}','KycController@getRemitterDetails');

Route::get('/kyc-form/{remitter_id}','KycController@getRemitters');


Route::post('/postKyc', 'KycController@postKycForm');
