<?php

namespace Acme\EventSubscriber;
use Acme\Mailer\Mail;
use Acme\SMS\SMS;
use \Log;

class UserRegistration {

  public function sendEmailVerificationLink ($user)
  {
    $token = md5(time().'_'.$user->id);
    $user->email_token = $token;
    $user->save();
    $subject = 'Email verification from SonaCoin';
    $content = 'Please verify you\'re email address. Click here <a href="'.getBU().'/verification/email/'.$token.'">Verify</a>';
    Mail::send($user->email, $subject, $content);
  }
  public function sendPhoneVerificationOTP ($user)
  {
    $otp = mt_rand(10000, 99999);
    $user->sms_token = $otp;
    $user->save();
    $content = 'Your OTP from SonaCoin is '.$otp;
    SMS::send($user->phone_no, $content);
  }

  public function subscribe ($events)
  {
    $events->listen('user.registered', 'Acme\EventSubscriber\UserRegistration@sendEmailVerificationLink');
    $events->listen('user.registered', 'Acme\EventSubscriber\UserRegistration@sendPhoneVerificationOTP');
  }

}