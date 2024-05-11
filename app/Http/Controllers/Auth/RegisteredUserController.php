<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Services\RabbitMQSendToExhangeService;



class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */

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

    public function create(): View
    {
        return view('auth.register');
    }


    public function store(Request $request): RedirectResponse
    {
        dd('test');
        $userData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => 'nullable|string|max:20',
            'birthday' => 'nullable|date',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:20',
            'street' => 'nullable|string|max:255',
            'house_number' => 'nullable|string|max:20',
            'company_email' => 'nullable|string|email|max:255',
            'company_id' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'user_role' => 'required|string|in:Speaker,Individual,Employee',
            'invoice' => 'required|string|in:Yes,No',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the user
        $user = User::create([
            'name' => $userData['first_name'] . ' ' . $userData['last_name'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
            'telephone' => $userData['telephone'],
            'birthday' => $userData['birthday'],
            'country' => $userData['country'],
            'state' => $userData['state'],
            'city' => $userData['city'],
            'zip' => $userData['zip'],
            'street' => $userData['street'],
            'house_number' => $userData['house_number'],
            'company_email' => $userData['company_email'],
            'company_id' => $userData['company_id'],
            'source' => $userData['source'],
            'user_role' => $userData['user_role'],
            'invoice' => $userData['invoice'],
        ]);

        $xmlMessage = new \SimpleXMLElement('<user/>');
        $xmlMessage->addChild('routing_key', 'user.crm');
        $xmlMessage->addChild('user_id', $user->id);
        $xmlMessage->addChild('first_name', $userData['first_name']);
        $xmlMessage->addChild('last_name', $userData['last_name']);
        $xmlMessage->addChild('email', $userData['email']);
        $xmlMessage->addChild('telephone', $userData['telephone'] ?? '');
        $xmlMessage->addChild('birthday', $userData['birthday'] ?? '');
        
        $address = $xmlMessage->addChild('address');
        $address->addChild('country', $userData['country'] ?? '');
        $address->addChild('state', $userData['state'] ?? '');
        $address->addChild('city', $userData['city'] ?? '');
        $address->addChild('zip', $userData['zip'] ?? '');
        $address->addChild('street', $userData['street'] ?? '');
        $address->addChild('house_number', $userData['house_number'] ?? '');

        $xmlMessage->addChild('calendar_link', 'www.example.com');

        // Convert XML to string
        $message = $xmlMessage->asXML();
        dd($message);
        // Send message to RabbitMQ
        $routingKey = 'user.frontend';

        $this->sendMessageToTopic($routingKey, $message);

        //event(new Registered($user));

       // Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
