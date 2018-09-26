<?php

use Acme\Auth\Auth;

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

Route::filter('auth', function()
{
	if (! Auth::user())
	{
		return View::make('home.unauthorized');
		return Redirect::to('/login');
	}
});

Route::filter('auth.json', function()
{
	if (! Auth::user())
	{
		return Response::json(['message' => 'Please login to continue.'], 403);
	}
});

Route::filter('guest', function()
{
	if (Auth::user())
		return Redirect::to('/');
});

Route::filter('guest.json', function()
{
	if (Auth::user())
		return Response::json(['message' => 'You\'re already logged in.'], 500);
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});
