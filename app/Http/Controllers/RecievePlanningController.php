<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use Carbon\Carbon;

class RecievePlanningController extends Controller
{
    
    public function consume()
    {
/*
        // Establish connection to RabbitMQ
        $connection = new AMQPStreamConnection('10.2.160.51', 5672, 'user', 'password');
        $channel = $connection->channel();
        
        // Declare the queue
        $channel->queue_declare('frontend', false, true, false, false);
        
        // Bind the queue to the exchange with the routing key 'event.planning'
        $channel->queue_bind('frontend', 'amq.topic', 'event.planning');

        echo " [*] Waiting for messages. To exit press CTRL+C\n";
        
        $callback = function ($msg) {
            $xml = simplexml_load_string($msg->body);
    
            $events = $xml->xpath('//event');
            foreach ($events as $eventData) {
                $event = new Event();
                $event->date = Carbon::createFromFormat('Y-m-d', (string) $eventData->date)->toDateString();
                $event->routing_key = (string) $eventData->routing_key;
                $event->external_id = (int) $eventData->id;
                $event->crud_operation = (int) $eventData->crud_operation;
                $event->start_time = (string) $eventData->start_time;
                $event->end_time = (string) $eventData->end_time;
                $event->location = (string) $eventData->location;
                $event->speaker_user_id = (int) $eventData->speaker->user_id;
                $event->speaker_company_id = (int) $eventData->speaker->company_id;
                $event->max_registrations = (int) $eventData->max_registrations;
                $event->available_seats = (int) $eventData->available_seats;
                $event->description = (string) $eventData->description;
                $event->save();
            }
    
            echo 'Events saved successfully', "\n";
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };
    
        // Set up consumer to receive messages from the queue
        $channel->basic_consume('frontend', '', false, false, false, false, $callback);
    
        // Start consuming messages
        while (count($channel->callbacks)) {
            $channel->wait();
        }
    
        // Close channel and connection
        $channel->close();
        $connection->close();
*/
    }
}
