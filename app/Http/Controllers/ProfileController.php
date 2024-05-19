<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\RabbitMQSendToExhangeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

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
    public function update(Request $request): RedirectResponse
    {
        try {
            // Retrieve the authenticated user
            $user = $request->user();
            

            // Fill the user model with validated data from the request
            $user->fill($request->validated());

            // If the email is being updated, reset email verification
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            die('oke');

            // Save the updated user
            $user->save();

            // Redirect back to the profile edit page with a success message
            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        } catch (\Exception $e) {
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
            'Service' => 'frontend',
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

        if ($masterUuid !== null) {
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

            // Convert XML to string
            $message = $xmlMessage->asXML();

            // Send message to RabbitMQ
            $routingKey = 'user.frontend';

            $this->sendMessageToTopic($routingKey, $message);

            // Logout and delete user
            Auth::logout();

            $user->delete();

            try {
                $data_delete = [
                    'MASTERUUID' => $masterUuid,
                    'NewServiceId' => 'NULL',
                    'Service' => 'frontend',
                ];

                $response = $client->request('POST', 'http://10.2.160.51:6000/updateServiceId', [
                    'json' => $data_delete
                ]);
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                // Handle the exception
                echo $e->getMessage();
            }
        } else {
            // Handle the case where $masterUuid is not defined
            echo 'Failed to obtain UUID, cannot proceed with user deletion.';
            return redirect()->back()->withErrors('Failed to obtain UUID, cannot proceed with user deletion.');
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return view('user.home');
    }

}
