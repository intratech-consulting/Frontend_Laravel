<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\RabbitMQSendToExhangeService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Ramsey\Uuid\Uuid;
use App\Models\Event;
use App\Models\Attendance;

class EventRegistrationController extends Controller
{
    //
    protected $rabbitMQService;

    public function __construct(RabbitMQSendToExhangeService $rabbitMQService)
    {
        $this->rabbitMQService = $rabbitMQService;
    }

    public function sendMessageToTopic($routingKey, $message)
    {
        try {
            // stuur message naar rabbitMQ
            \Log::info('Sending message to RabbitMQ' . $message . ' with routing key ' . $routingKey);
            $this->rabbitMQService->sendMessageToTopic($routingKey, $message);
            $this->rabbitMQService->sendLogEntryToTopic('send_event_to_queue', 'Event registration message sent successfully', false);

            return response()->json(['message' => 'Message sent successfully'], 200);
        } catch (\Exception $e) {
            \Log::error('Error sending message to RabbitMQ: ' . $e->getMessage());
            $this->rabbitMQService->sendLogEntryToTopic('send_event_to_queue', 'Event registration message not sent: ' . $e->getMessage(), true);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function register(Request $request)
    {
        $user = Auth::user();
        $eventId = $request->input('event_id');

        $event = Event::findOrFail($eventId);

        // check of user al bestaat
        $existingAttendance = Attendance::where('user_id', $user->id)
                                        ->where('event_id', $eventId)
                                        ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('error', 'Je bent al ingeschreven voor dit event.');
        }

        if ($event->available_seats <= 0) {
            return redirect()->back()->with('error', 'Het maximum aantal inschrijvingen voor dit event is bereikt.');
        }

        // maak een nieuwe attendance
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'event_id' => $eventId,
        ]);

        \Log::info('Attendance created: ' . $attendance);

        $event->available_seats -= 1;
        $event->save();
        

        try{

        //get masterUuid from user
        $userMasterUuid = null;


        // Create a new Guzzle HTTP client
        $client = new \GuzzleHttp\Client();

        // Define the data for the request
        $data = [
            'ServiceId' => $user->id,
            'Service' => 'frontend',
        ];

        try {
            $response = $client->post('http://' . env('GENERAL_IP') . ':6000/getMasterUuid', [
                'json' => $data
            ]);

            // Get the response body
            $body = $response->getBody();

            \Log::info('get UUID Response Body: ' . $body);

            // Decode the JSON response
            $json = json_decode($body, true);

            // Get the MASTERUUID from the response
            $userMasterUuid = $json['UUID'];

            \Log::info('get user masterUuid: ' . $userMasterUuid);

            // Now you can use $masterUuid for whatever you need
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            \Log::error('Failed to retrieve masterUuid: ' . $e->getMessage());
            // Send logs to ControlRoom
            $this->rabbitMQService->sendLogEntryToTopic('get_UUID', 'Error: ' . $e->getMessage(), true);

            // Handle the exception
            throw new \Exception('Failed to retrieve masterUuid: ' . $e->getMessage());
        }

        //get masterUuid from event
        $eventMasterUuid = null;


        // Create a new Guzzle HTTP client
        $client = new \GuzzleHttp\Client();

        // Define the data for the request
        $data = [
            'ServiceId' => $event->id,
            'Service' => 'frontend',
        ];

        try {
            $response = $client->post('http://' . env('GENERAL_IP') . ':6000/getMasterUuid', [
                'json' => $data
            ]);

            // Get the response body
            $body = $response->getBody();

            \Log::info('get UUID Response Body: ' . $body);

            // Decode the JSON response
            $json = json_decode($body, true);

            // Get the MASTERUUID from the response
            $eventMasterUuid = $json['UUID'];

            \Log::info('get masterUuid: ' . $eventMasterUuid);

            // Now you can use $masterUuid for whatever you need
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            \Log::error('Failed to retrieve masterUuid: ' . $e->getMessage());
            // Send logs to ControlRoom
            $this->rabbitMQService->sendLogEntryToTopic('get_UUID', 'Error: ' . $e->getMessage(), true);

            // Handle the exception
            throw new \Exception('Failed to retrieve masterUuid: ' . $e->getMessage());
        }

        //haal id op van attendance
        $createdAttendance = Attendance::where('user_id', $user->id)
                                        ->where('event_id', $eventId)
                                        ->first();


        //create masterUuid from attendance
        $attendanceMasterUuid = null;

        $client = new \GuzzleHttp\Client();

        $data = [
            'ServiceId' => $createdAttendance->id,
            'Service' => 'frontend',
        ];

        try {
            \Log::info('create attendance masterUuid: ' . $data);
            $response = $client->post('http://' . env('GENERAL_IP') . ':6000/createMasterUuid', [
                'json' => $data
            ]);

            // Get the response body
            $body = $response->getBody();

            \Log::info('create UUID Response Body: ' . $body);

            // Decode the JSON response
            $json = json_decode($body, true);

            // Get the MASTERUUID from the response
            $attendanceMasterUuid = $json['MasterUuid'];

            \Log::info('create attendance masterUuid: ' . $attendanceMasterUuid);

            // Now you can use $masterUuid for whatever you need
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            \Log::error('Failed to retrieve masterUuid: ' . $e->getMessage());
            // Send logs to ControlRoom
            $this->rabbitMQService->sendLogEntryToTopic('get_UUID', 'Error: ' . $e->getMessage(), true);

            // Handle the exception
            throw new \Exception('Failed to retrieve masterUuid: ' . $e->getMessage());
        }

        \Log::info("I don't know how, but we are here!");
        // Create XML message
        $xmlMessage = new \SimpleXMLElement('<attendance/>');
        $xmlMessage->addChild('routing_key', 'attendance.frontend');
        $xmlMessage->addChild('crud_operation', 'create');
        $xmlMessage->addChild('id', $attendanceMasterUuid);
        $xmlMessage->addChild('user_id', $userMasterUuid);
        $xmlMessage->addChild('event_id', $eventMasterUuid);

        // Convert XML to string
        $message = $xmlMessage->asXML();

        // Send message to RabbitMQ
        $routingKey = 'attendance.frontend';

        $this->sendMessageToTopic($routingKey, $message);

        //send log
        $this->rabbitMQService->sendLogEntryToTopic('subscribe', 'User (id: ' . $user->id .  ', name: ' . $user->first_name . " " . $user->last_name . 'subscribed successfully to event: ' . $attendance->event->title , false);

        return redirect()->back()->with('success', 'Je bent succesvol ingeschreven voor het event ' . $attendance->event->title . '!');

        }
        catch(\Exception $e)
        {
        //send log
        $this->rabbitMQService->sendLogEntryToTopic('subscribe', 'Error: [User (id: ' . $user->id .  ', name: ' . $user->first_name .  " " . $user->last_name . ' unsubscribed unsuccessfully from event: ' . $attendance->event->title . '] -> ' . $e->getMessage(), true);

        return redirect()->back()->with('failed', 'Je bent niet succesvol ingeschreven voor het event ' . $attendance->event->title . '!');
        }
    }
}
