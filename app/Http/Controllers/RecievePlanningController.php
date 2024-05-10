<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\DB;
use App\Models\Event;

class RecievePlanningController extends Controller
{
    
public function consume()
{
    $connection = new AMQPStreamConnection('10.2.160.51', 15672, 'user', 'password');
    $channel = $connection->channel();
    
    $channel->queue_declare('planning', false, true, false, false);
    
    $callback = function ($msg) {
        $xml = simplexml_load_string($msg->body);

        // Display the XML content
        echo "Received XML:\n";
        echo $xml->asXML(); // Echo the XML content

        $events = $xml->xpath('//event');
        foreach ($events as $eventData) {
            $event = new Event();
            $event->date = Carbon::createFromFormat('Y-m-d', (string) $eventData->date)->toDateString();
            $event->start_time = (string) $eventData->start_time;
            $event->end_time = (string) $eventData->end_time;
            $event->location = (string) $eventData->location;
            $event->speaker_name = (string) $eventData->speaker->name;
            $event->speaker_email = (string) $eventData->speaker->email;
            $event->speaker_company = (string) $eventData->speaker->company;
            $event->max_registrations = (int) $eventData->max_registrations;
            $event->available_seats = (int) $eventData->available_seats;
            $event->description = (string) $eventData->description;
            $event->save();
        }

        echo 'Events saved successfully', "\n";
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    };

    $channel->basic_consume('planning', '', false, false, false, false, $callback);

    while (count($channel->callbacks)) {
        $channel->wait();
    }

    $channel->close();
    $connection->close();
}


}
