<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQSendToExhangeService
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

    public function sendMessageToTopic($routingKey, $message)
    {
        try {
            // Declare the amq.topic exchange
            $this->channel->exchange_declare('amq.topic', 'topic', false, true, false);
    
            // Create a new message
            $msg = new AMQPMessage($message);
    
            // Publish the message to the amq.topic exchange with the specified routing key
            $this->channel->basic_publish($msg, 'amq.topic', $routingKey);
    
            return true; // Message sent successfully
        } catch (\Exception $e) {
            throw new \Exception("Failed to send message to amq.topic exchange: " . $e->getMessage());
        }
    }

    public function closeConnection()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
