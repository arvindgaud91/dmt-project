<?php

namespace Acme\EventSubscriber;
use \Log;

class Login {

  public function recordLastLogin ($user)
  {
    $login = LastLogin::create([
      user_id => $user->id,
      ip => Request::getClientIp()
    ]);
  }

  public function subscribe ($events)
  {
    $events->listen('user.login', 'Acme\EventSubscriber\Login@recordLastLogin');
  }

}