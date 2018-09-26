<?php
use Acme\Auth\Auth;

class ModulesController extends BaseController
{
	public function postRegister ()
	{
		if (! Input::has('auth_token')) {
			return Response::json(['message' => 'Unauthorized'], 401);
		}
		if (Input::get('auth_token') !== 'uatauthtoken') {
			return Response::json(['message' => 'Unauthorized'], 403);
		}
		$rules = [
      'name' => 'required',
      'email' => 'required|email|unique:users',
      'phone_no' => 'required',
      'password' => 'required|min:5',
      'password_conf' => 'required|same:password'
    ];
    $validator = Validator::make(Input::all(), $rules);
    if ($validator->fails()) {
      return Response::json(['code' => 1, 'message' => 'Validation failed.', 'errors' => $validator->messages()], 422);
    }
    $user = new User(Input::only('name', 'email', 'phone_no', 'password'));
    $user->password = Hash::make(Input::get('password'));
    $user->status = 1;
    $user->type = 3;
    $user->save();

    return array_merge($user->toArray(), ['email_token' => $user->email_token, 'sms_token' => $user->sms_token]);
	}

	public function postLogin ()
	{
		if (! Input::has('auth_token')) {
			return Response::json(['message' => 'Unauthorized'], 401);
		}
		if (Input::get('auth_token') !== 'uatauthtoken') {
			return Response::json(['message' => 'Unauthorized'], 403);
		}
		if (! Input::has('phone_no') || ! Input::has('password')) {
			return Response::json(['code' => 1, 'message' => 'Validation failed'], 422);
		}

		$user = Auth::validate(Input::only('phone_no', 'password'));
    if (! $user)
    {
      return Response::json(['code' => 2, 'message' => 'Invalid credentials'], 422);
    }
    // if ($user->email_verified == 0) {
    //   return Response::json(['message' => 'Email is not yet verified.', 'code' => 2], 403);
    // }
    if ($user->phone_verified == 0) {
      return Response::json(
        ['message' => 'Mobile no is not yet verified.', 'code' => 3, 'email' => $user->email], 422);
    }
    if ($user->status == 0)
    {
      return Response::json(
        ['message' => 'Account is deactive.', 'code' => 4, 'email' => $user->email], 422);
    }

    return ['auth_token' => Auth::generateLoginToken($user)];
	}
}