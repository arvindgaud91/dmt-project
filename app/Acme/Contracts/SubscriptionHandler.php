<?php
namespace Acme\Contracts;

/*
 * Abstract class defining the method needed to be implemented by the Job handlers.
 * Will store and keep the message inorder to enable job acknowledgements once completed.
 *
 */
abstract class SubscriptionHandler
{
	private $message;
  abstract public function handle ($payload);

  public function setMessage ($message) {
  	$this->message = $message;
  }

  /*
   * To be called after completing a job.
   * Will tell the Queue that the job is completed, or else the queue will reschedule the job on the connection breaking with the consumer.
   *
   */
  public function acknowledgeSuccess ()
  {
		$this->message->delivery_info['channel']->basic_ack($this->message->delivery_info['delivery_tag']);
  }
}
