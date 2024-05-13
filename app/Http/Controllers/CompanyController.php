<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyController extends Controller
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

    public function create_company(Request $request)
    {
       
        $companyData = $request->validate([

            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'telephone' => ['required', 'string', 'max:20'],
            'logo' => ['nullable', 'image', 'max:2048'], // Max file size in kilobytes (2MB)
            'country' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'zip' => ['required', 'string', 'max:20'],
            'street' => ['required', 'string', 'max:255'],
            'house_number' => ['required', 'string', 'max:20'],
            'type' => ['required', 'string', 'max:255'],
            'invoice' => ['required', 'string', 'max:255'],

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
        // Create the company
      



*/
        
  $xmlCompany = new \SimpleXMLElement('<company/>');


    $xmlCompany->addChild('routing_key', 'user.crm');
        $xmlCompany->addChild('crud_operation', 'create');
        $xmlCompany->addChild('id', mt_rand(10000, 99999)); // Generate a random ID
        $xmlCompany->addChild('name', $request->input('name'));
        $xmlCompany->addChild('email', $request->input('email'));
        $xmlCompany->addChild('telephone', $request->input('telephone'));
        $xmlCompany->addChild('logo', $request->input('logo', ''));
        
        $address = $xmlCompany->addChild('address');
        $address->addChild('country', $request->input('country'));
        $address->addChild('state', $request->input('state'));
        $address->addChild('city', $request->input('city'));
        $address->addChild('zip', $request->input('zip'));
        $address->addChild('street', $request->input('street'));
        $address->addChild('house_number', $request->input('house_number'));
        
        $xmlCompany->addChild('type', $request->input('type'));
        $xmlCompany->addChild('invoice', $request->input('invoice'));

        // Convert XML to string
        $message = $xmlCompany->asXML();
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
