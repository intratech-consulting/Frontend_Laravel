<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\RabbitMQSendToExhangeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use GuzzleHttp\Client;
use Exception;

class EmployeeController extends Controller
{
    protected $rabbitMQService;

    public function __construct(RabbitMQSendToExhangeService $rabbitMQService)
    {
        $this->rabbitMQService = $rabbitMQService;
    }

    public function sendMessageToTopic($routingKey, $message)
    {
        try {
            // Send message to the amq.topic exchange using RabbitMQSendService
            $this->rabbitMQService->sendMessageToTopic($routingKey, $message);

            return response()->json(['message' => 'Message sent successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function register(Request $request)
    {
        $userData = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'telephone' => ['required', 'string', 'max:20'],
            'birthday' => ['required', 'date'],
            'country' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'zip' => ['required', 'string', 'max:20'],
            'street' => ['required', 'string', 'max:255'],
            'house_number' => ['required', 'string', 'max:20'],
            'invoice' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'user_role' => ['required', 'string', Rule::in(['employee', 'speaker'])],
        ]);

        try {
            $company = Auth::user(); // Assuming the company is logged in and this action is protected by auth middleware

            $user = User::create([
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
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
                'invoice' => $userData['invoice'],
                'user_role' => $userData['user_role'],
                'company_id' => $company->id,
            ]);

            $client = new Client();
            $data = [
                'Service' => 'frontend',
                'ServiceId' => $user->id,
            ];

            $response = $client->post('http://' . env('GENERAL_IP') . ':6000/createMasterUuid', [
                'json' => $data
            ]);

            $body = $response->getBody();
            $json = json_decode($body, true);
            $masterUuid = $json['MasterUuid'];

            $xmlMessage = new \SimpleXMLElement('<user/>');
            $xmlMessage->addChild('routing_key', 'user.frontend');
            $xmlMessage->addChild('crud_operation', 'create');
            $xmlMessage->addChild('id', $masterUuid);
            $xmlMessage->addChild('first_name', $userData['first_name']);
            $xmlMessage->addChild('last_name', $userData['last_name']);
            $xmlMessage->addChild('email', $userData['email']);
            $xmlMessage->addChild('telephone', $userData['telephone']);
            $xmlMessage->addChild('birthday', $userData['birthday']);

            $address = $xmlMessage->addChild('address');
            $address->addChild('country', $userData['country']);
            $address->addChild('state', $userData['state']);
            $address->addChild('city', $userData['city']);
            $address->addChild('zip', $userData['zip']);
            $address->addChild('street', $userData['street']);
            $address->addChild('house_number', $userData['house_number']);

            $xmlMessage->addChild('user_role', $userData['user_role']);
            $xmlMessage->addChild('company_id', $company->id);
            $xmlMessage->addChild('invoice', $userData['invoice']);
            $xmlMessage->addChild('source', 'frontend');

            $message = $xmlMessage->asXML();
            $routingKey = 'user.frontend';
            $this->sendMessageToTopic($routingKey, $message);

            $this->rabbitMQService->sendLogEntryToTopic('create user', 'User (masterUuid: ' . $masterUuid . ', name: ' . $user->first_name . ' ' . $user->last_name . ') created successfully', false);

            return redirect()
                ->route('user.home')
                ->with('success', 'Je account is succesvol aangemaakt ' . $user->first_name . ' ' . $user->last_name . '!');
        } catch (Exception $e) {
            $this->rabbitMQService->sendLogEntryToTopic('create user', 'Error: [User (name: ' . $userData['first_name'] . ' ' . $userData['last_name'] . ') failed to create successfully] -> ' . $e->getMessage(), true);

            return redirect()->back()->withErrors(['failed' => 'Je account is niet succesvol aangemaakt ' . $userData['first_name'] . ' ' . $userData['last_name'] . '!']);
        }
    }
}
