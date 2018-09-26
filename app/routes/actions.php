<?php

Route::post('/api/v1/actions/password-reset-otp', 'ActionsController@postPasswordResetOTP');
Route::post('/api/v1/actions/new-password-otp', 'ActionsController@postNewPasswordOTP');

Route::get('sessions', function(){
	dd(\Cookie::get('user'));
});