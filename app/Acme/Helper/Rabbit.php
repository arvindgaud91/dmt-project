<?php
namespace Acme\helper;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Acme\Contracts\SubscriptionHandler;

/**
* RabbitMQ helper class
*/
class Rabbit
{
	private $connection;
	private $channel;
	private $queue;

	function __construct($queue)
	{
		$this->connection = new AMQPStreamConnection(getenv('RABBIT.HOST'), getenv('RABBIT.PORT'), getenv('RABBIT.USERNAME'), getenv('RABBIT.PASSWORD'));
		$this->channel = $this->connection->channel();
		$this->queue = $queue;
	}

	/*
	 * @params $payload: Associative array with information about the job (must contain a key 'Action')
	 * Will help publish the job to the Queue
	 *
	 */
	public function publish ($payload)
	{
		$this->channel->queue_declare($this->queue, false, true, false, false);
		$msg = new AMQPMessage(json_encode($payload), ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
		$response = $this->channel->basic_publish($msg, '', $this->queue);
		$this->channel->close();
		$this->connection->close();
		return $response;
	}

	/*
	 * @params $handler: Object for type Subscriptionhandler
	 * Will maintain a connection to the queue.
	 * Will trigger the 'handle' method on the handler, passing it the Job
	 *
	 */
	public function subscribe (SubscriptionHandler $handler)
	{
		$this->channel->queue_declare($this->queue, false, true, false, false);

		$callback = function($msg) use ($handler) {
			$handler->setMessage($msg);
  		$handler->handle(json_decode($msg->body));
		};

		$this->channel->basic_qos(null, 1, null);
		$this->channel->basic_consume($this->queue, '', false, false, false, false, $callback);

		while(count($this->channel->callbacks)) {
    	$this->channel->wait();
		}
		$this->channel->close();
		$this->connection->close();
	}

	public function getChannel ()
	{
		return $this->channel;
	}
}
