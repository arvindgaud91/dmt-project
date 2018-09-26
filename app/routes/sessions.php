<?php

/**
 * Routes dealing with showing login page and handling login request
 *
 */
// Route::get('/login', 'SessionsController@getLogin');
	Route::get('/login', function () {
		return Redirect::to(getenv('AUTH_URL'));
	});

Route::post('/login', 'SessionsController@postLogin');
Route::post('/login-global', 'SessionsController@postLoginGlobal');

/**
 * Routes dealing with showing register page and handling register request
 *
 */
Route::get('/register', 'SessionsController@getRegister');
Route::post('/register', 'SessionsController@postRegister');

/**
 * Routes dealing with logging out user
 *
 */
Route::get('/logout', 'SessionsController@getLogout');

/**
 * Routes dealing with Email and Phone verification
 *
 */
Route::get('/verification/email/{token}', 'SessionsController@getVerifyEmail');
Route::post('/verification/phone/{otp}', 'SessionsController@postVerifyPhone');

/**
 * Routes dealing with resending Email and Phone verification tokens
 *
 */
Route::post('/verification/email/actions/resend', 'SessionsController@postResendEmailToken');
Route::post('/verification/phone/actions/resend', 'SessionsController@postResendSMSToken');

/**
 * Routes dealing with Password reset
 *
 */
Route::post('/password/actions/reset-token', 'SessionsController@postSetPasswordResetToken');
Route::get('/password/actions/reset-token/{token}', 'SessionsController@getPasswordResetPage');
Route::post('/password/actions/reset-token/{token}', 'SessionsController@postResetPassword');

Route::post('/api/v1/reset-bank-session', 'SessionsController@postResetBankSession');
