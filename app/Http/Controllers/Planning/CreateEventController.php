<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RabbitMQSendToExhangeService;
use App\Models\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;







class CreateEventController extends Controller
{
    protected $rabbitMQService;

 public function __construct(RabbitMQSendToExchangeService $rabbitMQService)
    {
        $this->rabbitMQService = $rabbitMQService;
    }

    public function createEvent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'max_registrations' => 'required|integer',
            'available_seats' => 'required|integer',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'title' => 'required|string|max:255',
            'speaker_name' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        $event = Event::create([
            'location' => $request->input('location'),
            'description' => $request->input('description'),
            'max_registrations' => $request->input('max_registrations'),
            'available_seats' => $request->input('available_seats'),
            'date' => $request->input('date'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'speaker_user_id' => $user->id,
            'speaker_company_id' => $user->company_id, // id van de ingelogde bedrijf
            'title' => $request->input('title'),
            'speaker_name' => $request->input('speaker_name')
        ]);

        $xmlMessage = new \SimpleXMLElement('<event/>');
        $xmlMessage->addChild('routing_key', 'event.crm');
        $xmlMessage->addChild('crud_operation', 'create');
        $xmlMessage->addChild('id', $event->id);
        $xmlMessage->addChild('title', $event->title);
        $xmlMessage->addChild('date', $event->date);
        $xmlMessage->addChild('start_time', $event->start_time);
        $xmlMessage->addChild('end_time', $event->end_time);
        $xmlMessage->addChild('location', $event->location);

        $speaker = $xmlMessage->addChild('speaker');
        $speaker->addChild('user_id', $event->speaker_user_id);
        $speaker->addChild('company_id', $event->speaker_company_id);
        $speaker->addChild('name', $event->speaker_name);

        $xmlMessage->addChild('max_registrations', $event->max_registrations);
        $xmlMessage->addChild('available_seats', $event->available_seats);
        $xmlMessage->addChild('description', $event->description);

        $message = $xmlMessage->asXML();

        $routingKey = 'event.crm';
        $this->sendMessageToTopic($routingKey, $message);

        return redirect()->route('home')->with('success', 'Evenement succesvol aangemaakt.');
    }

    public function sendMessageToTopic($routingKey, $message)
    {
        try {
            $this->rabbitMQService->sendMessageToTopic($routingKey, $message);
            return response()->json(['message' => 'Message sent successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
