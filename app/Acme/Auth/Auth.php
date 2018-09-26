<?php

namespace Acme\Auth;
use \Firebase\JWT\JWT;
use \User;
use \DmtVendor;
use \Hash;
use \ExpiredToken;
use \Request;


class Auth {

  protected $user = null;
  static $instancertrt = null;

  function __construct() {
    try {

      $key = getJWTKey();
      $tracker = \Cookie::get('tracker');
      if (! $tracker) {
        $authHeader = Request::header('auth');
        if ($authHeader) $tracker = $authHeader;
      }
      if (! $tracker) {
        return true;
      }
      
      $decoded = JWT::decode($tracker, $key, ['HS256']);
      if (! $decoded) {
        return true;
      }
      $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
      ];
      $body = \Unirest\Request\Body::json(['token' => $tracker]);
      $response = \Unirest\Request::post(getenv('AUTH_URL').'/api/auth/v1/user', $headers, $body);
      $user = $response->body;
      //dd($user);
      //$user->dmt_vendor = DmtVendor::where('user_id', $user->id)->first(); 
      $this->user = $user;
      //if (! $user->dmt_vendor) $this->user = null;
      if ($user->status == 0) $this->user = null;
      if ($user->type != 4) $this->user = null;
      // Setting dummy user
      // $this->user= (object) ['name' => 'karan', 'email' => 'karan@gmail.com']; 

    } catch (Exception $e) {
        
      return true;
    } finally {
        //dd($user);
      return true;
    }
  }

  public static function validate ($credentials)
  {
    if (! $credentials['phone_no'] || ! $credentials['password']) {
      return FALSE;
    }
    $user = \User::where('phone_no', $credentials['phone_no'])->first();
    if (! $user) {
      return FALSE;
    }
    if ($user->type != 4 && $user->type != 3) {
      return FALSE;
    }
    if (! \Hash::check($credentials['password'], $user->password)) {
      return FALSE;
    }
    return $user;
  }

  public static function attempt ($credentials)
  {
    $user = static::validate($credentials);
    if (! $user) return false;
    if ($user->status == 0) return false;
    return static::login($user);
  }

  public static function login ($user)
  {
    $key = getJWTKey();
    $token = [
      'iss' => getBU(),
      'iat' => (new \DateTime)->getTimestamp(),
      'user_id' => $user->id,
      'name' => $user->name,
      'privileges' => 'consumer'
    ];
    $jwt = JWT::encode($token, $key);
    \Cookie::queue('tracker', $jwt, 60*24*7);
    return $jwt;
  }

  public static function generateLoginToken ($user)
  {
    $key = getJWTKey();
    $token = [
      'iss' => getBU(),
      'iat' => (new \DateTime)->getTimestamp(),
      'user_id' => $user->id,
      'name' => $user->name,
      'privileges' => 'consumer'
    ];
    return JWT::encode($token, $key);
  }

  public static function user ()
  {
    $instance = static::getInstance();
    return $instance->user;
  }

  public static function logout ()
  {
    $instance = static::getInstance();
    $instance->user = null;
    \Cookie::queue(\Cookie::forget('tracker'));
     $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ];
    // $body = \Unirest\Request\Body::json(['token' => $tracker]);
    $response = \Unirest\Request::get(getenv('AUTH_URL').'/api/auth/v1/logout', $headers);
    return TRUE;
  }

  public static function getInstance ()
  {
    return static::$instancertrt
      ? static::$instancertrt
      : static::setInstance();
  }

  public static function setInstance ()
  {
    return static::$instancertrt = new self();
  }

}
