<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\RabbitMQSendToExhangeService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Ramsey\Uuid\Uuid;

class RegisteredUserController extends Controller
{
    protected $rabbitMQService;

    public function __construct(RabbitMQSendToExhangeService $rabbitMQService)
    {
        $this->rabbitMQService = $rabbitMQService;
    }

    public function sendMessageToTopic($routingKey, $message)
    {
        $xml = new \SimpleXMLElement($message);
        // Extract user ID and name from XML
        $userId = $xml->id->__toString();
        $firstName = $xml->first_name->__toString();
        $lastName = $xml->last_name->__toString();

        try{
            // Send message to the amq.topic exchange using RabbitMQSendService
            $this->rabbitMQService->sendMessageToTopic($routingKey, $message);
            $this->rabbitMQService->sendLogEntryToTopic('send_user_to_queue', 'User with UUIID: ' . $userId . ' and Name: ' .
                $firstName . ' ' . $lastName . ' Sent Successfully to User_Queue', false);

            return response()->json(['message' => 'Message sent successfully'], 200);
        } catch (\Exception $e) {
            $this->rabbitMQService->sendLogEntryToTopic('send_user_to_queue', 'User not Sent : ' . $e->getMessage(),
                true);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
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
            'company_email' => ['nullable', 'string', 'email', 'max:255'],
            'company_id' => ['nullable', 'integer'],
            'user_role' => ['required', 'string', Rule::in(['individual', 'employee', 'speaker'])],
        ]);

        do {
            // Generate a UUID and convert it to a string as long as its not unique
            $uuid = Uuid::uuid4()->toString();
        } while (User::find($uuid));

        $user = User::create([
            'id' => $uuid,
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
            'company_email' => isset($userData['company_email']) ? $userData['company_email'] : null,
            'company_id' => isset($userData['company_id']) ? $userData['company_id'] : null,
        ]);

        $userId = $user->id;

        \Log::info('User ID:  ' . print_r($userId, true));

        // Create a new Guzzle HTTP client
        $client = new \GuzzleHttp\Client();

        // Define the data for the request
        $data = [
            'Service' => 'frontend',
            'ServiceId' => $userId, // Assuming $userId is the ID of the newly created user
        ];

        try {
            // Make the POST request
            $response = $client->request('POST', 'http://10.2.160.51:6000/createMasterUuid', [
                'json' => $data
            ]);

            // Get the response body
            $body = $response->getBody();

            // Decode the JSON response
            $json = json_decode($body, true);

            // Get the MASTERUUID from the response
            $masterUuid = $json['MasterUuid'];

            // Now you can use $masterUuid for whatever you need
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            //Send logs to ControlRoom
            $this->rabbitMQService->sendLogEntryToTopic('make_UUID', 'Error: ' . $e->getMessage(), true);

            // Handle the exception
            throw new \Exception('Failed to retrieve masterUuid: ' . $e->getMessage());
        }


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

        $xmlMessage->addChild('company_email', isset($userData['company_email']) ? $userData['company_email'] : '');
        $xmlMessage->addChild('company_id', isset($userData['company_id']) ? $userData['company_id'] : '');
        $xmlMessage->addChild('source', 'frontend');
        $xmlMessage->addChild('user_role', $userData['user_role']);
        $xmlMessage->addChild('invoice', $userData['invoice']);
        $xmlMessage->addChild('calendar_link', '');

        // Convert XML to string
        $message = $xmlMessage->asXML();

        // Send message to RabbitMQ
        $routingKey = 'user.frontend';

        $this->sendMessageToTopic($routingKey, $message);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('user.home');
    }
}
