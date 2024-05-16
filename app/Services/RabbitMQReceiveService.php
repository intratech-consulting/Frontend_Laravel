<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQReceiveService
{
    private $connection;
    private $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection('10.2.160.51', 5672, 'user', 'password');
        $this->channel = $this->connection->channel();
    }

    public function receiveMessagesFromTopic($routingKey)
    {
        try {
            echo " [*] Waiting for messages. To exit press CTRL+C\n";

            list($queueName, ,) = $this->channel->queue_declare("", false, false, true, false);

            $this->channel->queue_bind($queueName, 'amq.topic', $routingKey);

            $callback = function ($msg) {
                echo 'Received message: ', $msg->body, "\n";

                // Parse the XML message
                $xml = new SimpleXMLElement($msg->body);

                // Map the XML data to the user fields
                $userData = [
                    'user_role' => (string) $xml->user_role,
                    'first_name' => (string) $xml->first_name,
                    'last_name' => (string) $xml->last_name,
                    'email' => (string) $xml->email,
                    'telephone' => (string) $xml->telephone,
                    'birthday' => (string) $xml->birthday,
                    'country' => (string) $xml->country,
                    'state' => (string) $xml->state,
                    'city' => (string) $xml->city,
                    'zip' => (string) $xml->zip,
                    'street' => (string) $xml->street,
                    'house_number' => (string) $xml->house_number,
                    'company_email' => (string) $xml->company_email,
                    'company_id' => (string) $xml->company_id,
                    'invoice' => (string) $xml->invoice,
                    'calendar_link' => (string) $xml->calendar_link,
                    'password' => (string) $xml->password,
                ];

                // Create a new User instance and save it to the database
                User::create($userData);

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

    private function closeConnection()
    {
        $this->channel->close();
        $this->connection->close();
    }
}