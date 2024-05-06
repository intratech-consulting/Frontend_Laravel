<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQReceiveService
{
    protected $connection;
    protected $channel;

    public function __construct()
    {
        // Establish RabbitMQ connection (use your connection details)
        $this->connection = new AMQPStreamConnection('10.2.160.51', 5672, 'user', 'password');
        $this->channel = $this->connection->channel();
    }

    public function receiveMessagesFromQueue($queueName)
    {
        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        // Declare the queue (if it doesn't exist)
        $this->channel->queue_declare($queueName, false, false, false, false);

        // Callback function to handle received messages
        $callback = function ($msg) {
            echo 'Received message: ', $msg->body, "\n";

            // Acknowledge the message (mark it as processed)
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };

        // Start consuming messages from the queue
        $this->channel->basic_consume($queueName, '', false, false, false, false, $callback);

        // Keep consuming until interrupted (e.g., with CTRL+C)
        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    public function closeConnection()
    {
        // Close channel and connection
        $this->channel->close();
        $this->connection->close();
    }
}
