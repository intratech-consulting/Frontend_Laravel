<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\ProfileUpdateRequest;
use App\Services\RabbitMQSendToExhangeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Ramsey\Uuid\Uuid;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
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

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        \Log::info('Update method called');

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'string|email|max:255' . $request->user()->id,
            'telephone' => 'nullable|string|max:20',
            'birthday' => 'nullable|date',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:10',
            'street' => 'nullable|string|max:255',
            'house_number' => 'nullable|string|max:10',
            'company_email' => 'nullable|string|email|max:255',
            'company_id' => 'nullable|string|max:255',
            'user_role' => 'string|max:255',
            'invoice' => 'nullable|string|max:255',
        ]);

        try {
            // Retrieve the authenticated user
            $user = $request->user();
            \Log::info('User before update: ' . print_r($user->toArray(), true));
    
            // Store the old email for comparison
            $oldEmail = $user->email;
    
            // Fill the user model with validated data from the request
            $user->fill($request->all());
            $user->save();
            \Log::info('User after update: ' . print_r($user->toArray(), true));
    
            // Assuming $userId is the ID of the user
            $userId = $user ->id;

            \Log::info('User ID:  ' . print_r($userId, true));
    
            // Create a new Guzzle HTTP client
            $client = new \GuzzleHttp\Client();
    
            // Define the data for the request
            $data = [
                'ServiceId' => $userId,
                'Service' => 'frontend'
            ];
        
            try {
                \Log::info('Fetching Master UUID with data: ' . print_r($data, true));
                // Make the POST request to get Master UUID
                $response = $client->post('http://10.2.160.51:6000/getMasterUuid', [
                    'json' => $data
                ]);
    
                // Get the response body
                $body = $response->getBody();
                \Log::info('UUID Response Body: ' . $body);
    
                // Decode the JSON response
                $json = json_decode($body, true);
                \Log::info('UUID Response: ' . print_r($json, true));
    
                // Check if UUID exists in the response
                if (isset($json['UUID'])) {
                    $masterUuid = $json['UUID'];
                } else {
                    throw new \Exception('UUID not found in response');
                }
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                \Log::error('RequestException: ' . $e->getMessage());
                return Redirect::back()->withErrors(['error' => 'Error retrieving UUID: ' . $e->getMessage()]);
            } catch (\Exception $e) {
                \Log::error('Exception: ' . $e->getMessage());
                return Redirect::back()->withErrors(['error' => 'Error retrieving UUID: ' . $e->getMessage()]);
            }
    
            // Create XML message for user update
            try {
                \Log::info('Creating XML message');
                $xmlMessage = new \SimpleXMLElement('<user/>');
                $xmlMessage->addChild('routing_key', 'user.frontend');
                $xmlMessage->addChild('crud_operation', 'update');
                $xmlMessage->addChild('id', $masterUuid);
                $xmlMessage->addChild('first_name', $user->first_name);
                $xmlMessage->addChild('last_name', $user->last_name);
                $xmlMessage->addChild('email', $user->email);
                $xmlMessage->addChild('telephone', $user->telephone);
                $xmlMessage->addChild('birthday', $user->birthday);
    
                $address = $xmlMessage->addChild('address');
                $address->addChild('country', $user->country);
                $address->addChild('state', $user->state);
                $address->addChild('city', $user->city);
                $address->addChild('zip', $user->zip);
                $address->addChild('street', $user->street);
                $address->addChild('house_number', $user->house_number);
    
                $xmlMessage->addChild('company_email', $user->company_email ?? '');
                $xmlMessage->addChild('company_id', $user->company_id ?? '');
                $xmlMessage->addChild('source', 'frontend');
                $xmlMessage->addChild('user_role', $user->user_role);
                $xmlMessage->addChild('invoice', $user->invoice);
                $xmlMessage->addChild('calendar_link', '');
    
                \Log::info('XML Message: ' . $xmlMessage->asXML());
    
                // Convert XML to string
                $message = $xmlMessage->asXML();
            } catch (\Exception $e) {
                \Log::error('XML Creation Exception: ' . $e->getMessage());
                throw new \Exception('Error creating XML message: ' . $e->getMessage());
            }

            // Update Service ID
            try {
                \Log::info('Updating Service ID with UUID: ' . $masterUuid);
                $data_update = [
                    'MASTERUUID' => $masterUuid,
                    'Service' => 'frontend',
                    'NewServiceId' => $userId
                ];
    
                $response = $client->post('http://10.2.160.51:6000/updateServiceId', [
                    'json' => $data_update
                ]);
                \Log::info('Service ID Update Response: ' . $response->getBody());
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                \Log::error('Service ID Update RequestException: ' . $e->getMessage());
                echo $e->getMessage();
            } catch (\Exception $e) {
                \Log::error('Service ID Update Exception: ' . $e->getMessage());
                echo $e->getMessage();
            }
    
            $routingKey = 'user.frontend';

            // Send message to RabbitMQ
            try {
                \Log::info('Sending message to RabbitMQ');
                $this->sendMessageToTopic($routingKey, $message);
            } catch (\Exception $e) {
                \Log::error('RabbitMQ Send Exception: ' . $e->getMessage());
                throw new \Exception('Error sending message to RabbitMQ: ' . $e->getMessage());
            }
    
            // Redirect back to the profile edit page with a success message
            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        } catch (\Exception $e) {
            \Log::error('Update failed: ' . $e->getMessage());
            // Handle any exceptions and redirect back with an error message
            return Redirect::back()->withErrors(['error' => 'An error occurred while updating your profile. Please try again later.']);
        }
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Assuming $userId is the ID of the user
        $userId = $user->id;

        // Create a new Guzzle HTTP client
        $client = new \GuzzleHttp\Client();

        // Define the data for the request
        $data = [
            'ServiceId' => $userId, // Assuming $userId is the ID of the user you want to delete
            'Service' => 'frontend'
        ];

        $masterUuid = null;

        try {
            // Make the POST request
            $response = $client->request('POST', 'http://10.2.160.51:6000/getMasterUuid', [
                'json' => $data
            ]);

            // Get the response body
            $body = $response->getBody();

            // Decode the JSON response
            $json = json_decode($body, true);

            // Check if UUID exists in the response
            if (isset($json['UUID'])) {
                $masterUuid = $json['UUID'];
            } else {
                // Handle the case where UUID is not present in the response
                throw new \Exception('UUID not found in response');
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle the Guzzle exception
            echo $e->getMessage();
        } catch (\Exception $e) {
            // Handle other exceptions
            echo $e->getMessage();
        }

            // Create XML message for user deletion
            $xmlMessage = new \SimpleXMLElement('<user/>');
            $xmlMessage->addChild('routing_key', 'user.frontend');
            $xmlMessage->addChild('crud_operation', 'delete');
            $xmlMessage->addChild('id', $masterUuid);
            $xmlMessage->addChild('first_name', $user->first_name);
            $xmlMessage->addChild('last_name', $user->last_name);
            $xmlMessage->addChild('email', $user->email);
            $xmlMessage->addChild('telephone', $user->telephone);
            $xmlMessage->addChild('birthday', $user->birthday);

            $address = $xmlMessage->addChild('address');
            $address->addChild('country', $user->country);
            $address->addChild('state', $user->state);
            $address->addChild('city', $user->city);
            $address->addChild('zip', $user->zip);
            $address->addChild('street', $user->street);
            $address->addChild('house_number', $user->house_number);

            $xmlMessage->addChild('company_email', $user->company_email ?? '');
            $xmlMessage->addChild('company_id', $user->company_id ?? '');
            $xmlMessage->addChild('source', 'frontend');
            $xmlMessage->addChild('user_role', $user->user_role);
            $xmlMessage->addChild('invoice', $user->invoice);
            $xmlMessage->addChild('calendar_link', '');

            try {
                $data_delete = [
                    'MASTERUUID' => $masterUuid,
                    'Service' => 'frontend',
                    'NewServiceId' => null
                ];

                $response = $client->request('POST', 'http://10.2.160.51:6000/updateServiceId', [
                    'json' => $data_delete
                ]);
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                // Handle the exception
                echo $e->getMessage();
            }

            // Convert XML to string
            $message = $xmlMessage->asXML();

            // Send message to RabbitMQ
            $routingKey = 'user.frontend';

            $this->sendMessageToTopic($routingKey, $message);

            // Logout and delete user
            Auth::logout();

            $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return view('user.home');
    }

}
