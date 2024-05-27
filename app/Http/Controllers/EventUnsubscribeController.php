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

class EventUnsubscribeController extends Controller
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

    public function unsubscribe(Request $request)
    {
        $user = Auth::user();
        $attendanceId = $request->input('attendances_id');
        $eventId = $request->input('event_id');
        $event = Event::find($eventId);

        //zoek attendance
        $attendance = Attendance::where('user_id', $user->id)
                                ->where('event_id', $eventId)
                                ->first();

        // Create XML message
        $xmlMessage = new \SimpleXMLElement('<attendance/>');
        $xmlMessage->addChild('routing_key', 'attendance.frontend');
        $xmlMessage->addChild('crud_operation', 'delete');
        $xmlMessage->addChild('id', $attendanceId);
        $xmlMessage->addChild('user_id', $user->id);
        $xmlMessage->addChild('event_id', $eventId);

        // Convert XML to string
        $message = $xmlMessage->asXML();

        // Send message to RabbitMQ
        $routingKey = 'attendance.frontend';

        $this->sendMessageToTopic($routingKey, $message);

        // verwijder attendance
        $attendance->delete();

        return redirect()->back()->with('success', 'Je bent succesvol uitgeschreven voor het event ' . $event->title . "!");
    }
}
