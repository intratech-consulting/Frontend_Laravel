<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQSendService
{
    protected $connection;
    protected $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection('10.2.160.51', 5672, 'user', 'password');
        $this->channel = $this->connection->channel();
    }

    public function sendMessageToQueue($queueName, $message)
    {
        $this->channel->queue_declare($queueName, false, false, false, false);

        $msg = new AMQPMessage($message);
        $this->channel->basic_publish($msg, '', $queueName);
    }

    public function closeConnection()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
