<?php

namespace Acme\Mailer;
use Log;

/**
 * Mailer Class implementing Send Grid
 */
class Mail
{
  public static function send ($to, $subject, $content, $from = 'no-reply@digitalindiapayments.com', $name = 'Digital Sathi')
  {
    if (! getenv('SEND_MAIL')) {
      Log::info('Mail being sent.');
      return;
    }
    $sendgrid = new \SendGrid(getenv('SENDGRID_KEY'));
		$email    = new \SendGrid\Email();
		$email->addTo($to)
					->setFrom($from)
          ->setFromName($name)
					->setSubject($subject)
					->setHtml($content);
		return $sendgrid->send($email);
  }
}
