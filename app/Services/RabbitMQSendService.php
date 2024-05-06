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
        try {
            $this->connection = new AMQPStreamConnection('10.2.160.51', 5672, 'user', 'password');
            $this->channel = $this->connection->channel();
        } catch (\Exception $e) {
            throw new \Exception("Failed to connect to RabbitMQ: " . $e->getMessage());
        }
    }

    public function sendMessageToQueue($queueName, $message)
    {
        try {
            // Check if the queue exists
            $queueInfo = $this->channel->queue_declare($queueName, passive: true);

            // If queue does not exist, throw an exception
            if (empty($queueInfo)) {
                throw new \Exception("Queue '$queueName' does not exist.");
            }

            // Create a new message
            $msg = new AMQPMessage($message);

            // Publish the message to the queue
            $this->channel->basic_publish($msg, '', $queueName);

            return true; // Message sent successfully
        } catch (\Exception $e) {
            throw new \Exception("Failed to send message to queue: " . $e->getMessage());
        }
    }

    public function closeConnection()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
