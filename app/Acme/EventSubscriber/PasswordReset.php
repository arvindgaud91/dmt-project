<?php

namespace Acme\EventSubscriber;
use \Log;
use \Acme\Mailer\Mail;

class PasswordReset {

  public function sendPasswordResetLink ($token)
  {
    $subject = 'Password reset request on SonaCoin account.';
    $content = 'Click on the following link to reset the password. <a href="'.getBU().'/password/actions/reset-token/'.$token->token.'">Reset password</a>';
    Mail::send($token->user->email, $subject, $content);
  }

  public function subscribe ($events)
  {
    $events->listen('user.password.reset-request', 'Acme\EventSubscriber\PasswordReset@sendPasswordResetLink');
  }

}