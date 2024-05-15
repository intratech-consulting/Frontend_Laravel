<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQReceiveService
{
    protected $connection;
    protected $channel;

    public function __construct(AMQPStreamConnection $connection)
    {
        $this->connection = $connection;
        $this->channel = $this->connection->channel();
    }

    public function receiveMessagesFromQueue($queueName)
    {
        try {
            echo " [*] Waiting for messages. To exit press CTRL+C\n";

            $this->channel->queue_declare($queueName, false, false, false, false);

            $callback = function ($msg) {
                echo 'Received message: ', $msg->body, "\n";

                // Process the message (e.g., save to database)
                // Example: $this->processMessage($msg->body);

                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
            };

            $this->channel->basic_consume($queueName, '', false, false, false, false, $callback);

            while ($this->channel->is_consuming()) {
                $this->channel->wait();
            }
        } catch (\Exception $e) {
            // Handle exceptions (e.g., log error, retry logic)
            // Example: Log::error('Error receiving messages: ' . $e->getMessage());
        } finally {
            $this->closeConnection();
        }
    }

    public function closeConnection()
    {
        if ($this->channel) {
            $this->channel->close();
        }
        if ($this->connection) {
            $this->connection->close();
        }
    }

    // Optional: Add method to process received messages based on your application's logic
    // private function processMessage($message) {}
}
