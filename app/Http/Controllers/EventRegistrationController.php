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
            $this->rabbitMQService->sendMessageToTopic($routingKey, $message);
            $this->rabbitMQService->sendLogEntryToTopic('send_event_to_queue', 'Event registration message sent successfully', false);

            return response()->json(['message' => 'Message sent successfully'], 200);
        } catch (\Exception $e) {
            $this->rabbitMQService->sendLogEntryToTopic('send_event_to_queue', 'Event registration message not sent: ' . $e->getMessage(), true);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function register(Request $request)
    {
        $user = Auth::user();
        $eventId = $request->input('event_id');

        // check of user al bestaat
        $existingAttendance = Attendance::where('user_id', $user->id)
                                        ->where('event_id', $eventId)
                                        ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('error', 'Dit event bestaat al.');
        }

        if ($event->available_seats <= 0) {
            return redirect()->back()->with('error', 'Het maximum aantal inschrijvingen voor dit event is bereikt.');
        }

        // maak een nieuwe attendance
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'event_id' => $eventId,
        ]);

        $event->available_seats -= 1;
        $event->save();

        try{

        // Create XML message
        $xmlMessage = new \SimpleXMLElement('<attendance/>');
        $xmlMessage->addChild('routing_key', 'attendance.frontend');
        $xmlMessage->addChild('crud_operation', 'create');
        $xmlMessage->addChild('id', $attendance->id);
        $xmlMessage->addChild('user_id', $user->id);
        $xmlMessage->addChild('event_id', $eventId);

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
