<?php
namespace App\Http\Controllers\Planning;

//require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;


$connection = new AMQPStreamConnection('10.2.160.51', 5672, 'user', 'password');
$channel = $connection->channel();

$channel->queue_declare('frontend', false, false, false, false);

// Bind the queue to the exchange with the routing key 'event.planning'
$channel->queue_bind('frontend', 'amq.topic', 'event.planning');

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    try {
        $xml = simplexml_load_string($msg->body);

        // Check if the XML has the correct structure
        if ($xml->getName() === 'event') {
            // Extract data from the XML
            $id = (string) $xml->id;
            $date = (string) $xml->date;
            $start_time = (string) $xml->start_time;
            $end_time = (string) $xml->end_time;
            $location = (string) $xml->location;
            $description = (string) $xml->description;
            $max_registrations = (int) $xml->max_registrations;
            $available_seats = (int) $xml->available_seats;

            // Process the extracted data as needed
            echo "Received Event ID: $id\n";
            echo "Date: $date\n";
            echo "Start Time: $start_time\n";
            echo "End Time: $end_time\n";
            echo "Location: $location\n";
            echo "Description: $description\n";
            echo "Max Registrations: $max_registrations\n";
            echo "Available Seats: $available_seats\n";

            // Acknowledge the message
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        } else {
            echo "Received invalid XML format: {$xml->getName()}\n";
        }
    } catch (Exception $e) {
        // Handle exceptions
        echo 'Error: ', $e->getMessage(), "\n";
    }
};

$channel->basic_consume('frontend', '', false, false, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();

?>
