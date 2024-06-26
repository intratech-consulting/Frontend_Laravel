<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RabbitMQSendToExhangeService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\Rule;




class testController extends Controller
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

    /*public function register(Request $request)
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
            'company_email' => isset($userData['company_email']) ? $userData['company_email'] : null,
            'company_id' => isset($userData['company_id']) ? $userData['company_id'] : null,
        ]);


        $userId = $user->id;

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
            // Handle the exception
            echo $e->getMessage();
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

       return view('user.home');
    }*/

}
