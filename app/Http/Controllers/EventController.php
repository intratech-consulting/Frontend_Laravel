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







class EventController extends Controller
{
    protected $rabbitMQService;




    public function __construct(RabbitMQSendToExhangeService $rabbitMQService)
    {
        $this->rabbitMQService = $rabbitMQService;
    }


    public function sendMessageToTopic($routingKey, $message)
    {
        try{
            // Send message to the amq.topic exchange using RabbitMQSendService
            $this->rabbitMQService->sendMessageToTopic($routingKey, $message);

            return response()->json(['message' => 'Message sent successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showDetails($id)
    {
        $event = Event::with(['users', 'companies'])->get();
        return view('user.event-details', compact('showdetails'));
    }


    public function registerToEvent(Request $request)
   {
    if (!Auth::check()){
        return redirect()->back();
    }
       $request->validate([
           // 'user_id' => 'required|exists:users,id',
           'event_id' => 'required|exists:events,id',
       ]);

       $userId = Auth::id(); // $request->input('user_id'); //id of logged in user
       $eventId = $request->input('event_id');
       
       //TODO: add register when new db

    //    $xml = new \SimpleXMLElement('<root/>');
    //    $xml->addChild('action', 'sign_in');
    //    $xml->addChild('user_id', $userId);
    //    $xml->addChild('event_id', $eventId);

    //    $message = $xml->asXML();

    //    $this->sendMessageToTopic('registerToEvent', $message);

    return redirect()->back();

   } 

    public function create_event(Request $request)
    {
       
        $eventData = $request->validate([

   'date' => ['required'],
    'start_time' => ['required'],
    'end_time' => ['required'],
    'location' => ['required'],
    'max_registrations' => ['required'],
    'available_seats' => ['required'],
    'description' => ['required'],
    'speaker_user_id' => ['required'],
    'speaker_company_id' => ['required'],

/*
        'date' => ['required', 'date'],
        'start_time' => ['required', 'date_format:H:i:s'],
        'end_time' => ['required', 'date_format:H:i:s', Rule::after('start_time')],
        'location' => ['required', 'string', 'max:255'],
        'max_registrations' => ['required', 'integer', 'min:0'],
        'available_seats' => ['required', 'integer', 'min:0'],
        'description' => ['required', 'string'],
        'speaker_user_id' => ['required', 'exists:users,id'],
        'speaker_company_id' => ['required', 'exists:companies,id'],
*/
        ]);
        /*
        // Create the event
      

*/


        



$xmlEvent = new \SimpleXMLElement('<event/>');

$xmlEvent->addChild('routing_key', 'user.crm');
    $xmlEvent->addChild('crud_operation', 'create');
    $xmlEvent->addChild('id', mt_rand(100000, 999999)); // Generate a random ID
    $xmlEvent->addChild('date', $eventData['date']);
    $xmlEvent->addChild('start_time', $eventData['start_time']);
    $xmlEvent->addChild('end_time', $eventData['end_time']);
    $xmlEvent->addChild('location', $eventData['location']);

    // Add speaker data as a child element
    $speaker = $xmlEvent->addChild('speaker');
    $speaker->addChild('user_id', $eventData['speaker_user_id']);
    $speaker->addChild('company_id', $eventData['speaker_company_id']);

    $xmlEvent->addChild('max_registrations', $eventData['max_registrations']);
    $xmlEvent->addChild('available_seats', $eventData['available_seats']);
    $xmlEvent->addChild('description', $eventData['description']);

        // Convert XML to string
        $message = $xmlEvent->asXML();
        // Send message to RabbitMQ
        $routingKey = 'user.frontend';

        $this->sendMessageToTopic($routingKey, $message);

        //event(new Registered($user));

       // Auth::login($user);

       return redirect()->back();
    }

    public function test(Request $request)
    {
        dd('test');
        
        $routingKey = 'user.frontend';

                // Validate the message
        $request->validate([
            'message' => 'required|string',
        ]);

            // Extract the message from the request
        $message = $request->input('message');
    
            // Call sendMessage method to send the message
         $this->sendMessageToTopic($routingKey, $message);

        return redirect()->back();


    }
}
