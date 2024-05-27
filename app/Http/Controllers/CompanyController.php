<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RabbitMQSendToExhangeService;
use App\Models\Company;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;



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
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg,webp', 'max:2048'],// Max file size in kilobytes (2MB)
            'country' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'zip' => ['required', 'string', 'max:20'],
            'street' => ['required', 'string', 'max:255'],
            'house_number' => ['required', 'string', 'max:20'],
            'type' => ['required', 'string', 'max:255'],
            'invoice' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoPath = $logo->store('logos', 'public'); // This line stores the file
        }
        else {
            $logoPath = null;
            $logo = null;
        }

        $company = Company::create([
            'user_role' => 'company',
            'name' => $companyData['name'],
            'email' => $companyData['email'],
            'telephone' => $companyData['telephone'],
            'logo' => $logoPath,
            'country' => $companyData['country'],
            'state' => $companyData['state'],
            'city' => $companyData['city'],
            'zip' => $companyData['zip'],
            'street' => $companyData['street'],
            'house_number' => $companyData['house_number'],
            'type' => $companyData['type'],
            'invoice' => $companyData['invoice'],
            'password' => Hash::make($companyData['password']),
        ]);

        $masterUuid = null;

        // Create a new Guzzle HTTP client
        $client = new \GuzzleHttp\Client();

        // Define the data for the request
        $data = [
            'Service' => 'frontend',
            'ServiceId' => $company->id, // Assuming $company->id is the ID of the newly created company
        ];

        try {
            $response = $client->post('http://' . env('GENERAL_IP') . ':6000/createMasterUuid', [
                'json' => $data
            ]);

            // Get the response body
            $body = $response->getBody();

            \Log::info('UUID Response Body: ' . $body);

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
        };

        $xmlCompany = new \SimpleXMLElement('<company/>');
        $xmlCompany->addChild('routing_key', 'company.frontend');
        $xmlCompany->addChild('crud_operation', 'create');

        $xmlCompany->addChild('id', $masterUuid); // give masterUuid as id
        $xmlCompany->addChild('name', $companyData['name']);
        $xmlCompany->addChild('email', $companyData['email']);
        $xmlCompany->addChild('telephone', $companyData['telephone']);
        $xmlCompany->addChild('logo', $logo);

        $address = $xmlCompany->addChild('address');
        $address->addChild('country', $companyData['country']);
        $address->addChild('state', $companyData['state']);
        $address->addChild('city', $companyData['city']);
        $address->addChild('zip', $companyData['zip']);
        $address->addChild('street', $companyData['street']);
        $address->addChild('house_number', $companyData['house_number']);

        $xmlCompany->addChild('type', $companyData['type']);
        $xmlCompany->addChild('invoice', $companyData['invoice']);


        // Convert XML to string
        $message = $xmlCompany->asXML();
        // Send message to RabbitMQ
        $routingKey = 'company.frontend';

        $this->sendMessageToTopic($routingKey, $message);

        return redirect()
            ->route('user.home')
            ->with('success', 'Je account is succesvol aangemaakt  ' . $company->name . '!');
            Auth::login($company);
    }

    public function editProfile()
    {
        $company = Auth::guard('company')->user();
        return view('profile.edit', compact('company'));
    }

    public function updateProfile(Request $request)
    {
        $company = Auth::guard('company')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'telephone' => 'required|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'country' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'zip' => 'required|string|max:10',
            'street' => 'required|string|max:100',
            'house_number' => 'required|string|max:10',
            'invoice' => 'required|string|max:34',
        ]);

        // Handle file upload
        if ($request->hasFile('logo')) {
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }

            $logo = $request->file('logo');
            $logoPath = $logo->store('logos', 'public');
            $company->logo = $logoPath;
        }
        else {
            // If request doesn't have a logo, check if company has a logo
            if ($company->logo) {
                // If company has a logo, retrieve the file with the logo path
                $logo = Storage::disk('public')->get($company->logo);
            }
            else{
                $logo = null;
            }
        }

        $company->update($request->all());

        try {
            // Retrieve the authenticated company
            $company = Auth::guard('company')->user();

            \Log::info('Authenticated company: ' . json_encode($company)); // Log the authenticated company

            // Store the old email for comparison
            $oldEmail = $company->email;

            // Fill the company model with validated data from the request
            $company->fill($request->all());
            $company->save();

            \Log::info('Company data after update: ' . json_encode($company)); // Log the updated company data

            // Create a new Guzzle HTTP client
            $client = new \GuzzleHttp\Client();

            // Define the data for the request
            $data = [
                'Service' => 'frontend',
                'ServiceId' => $company->id, // Assuming $companyId is the ID of the updated company
            ];
            \Log::info('Company ID: ' . $company->id);

            try {
                // Make the POST request to get Master UUID
                $response = $client->post('http://' . env('GENERAL_IP') . ':6000/getMasterUuid', [
                    'json' => $data
                ]);

                // Get the response body
                $body = $response->getBody();

                // Decode the JSON response
                $json = json_decode($body, true);

                \Log::info('Response from getMasterUuid: ' . json_encode($json)); // Log the response

                // Check if UUID exists in the response
                if (isset($json['UUID'])) {
                    $masterUuid = $json['UUID'];
                } else {
                    throw new \Exception('UUID not found in response');
                }
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                \Log::error('Error retrieving UUID: ' . $e->getMessage()); // Log the error
                return Redirect::back()->withErrors(['error' => 'Error retrieving UUID: ' . $e->getMessage()]);
            } catch (\Exception $e) {
                \Log::error('Error retrieving UUID: ' . $e->getMessage()); // Log the error
                return Redirect::back()->withErrors(['error' => 'Error retrieving UUID: ' . $e->getMessage()]);
            }

            // Create XML message for company update
            try {
                $xmlMessage = new \SimpleXMLElement('<company/>');
                $xmlMessage->addChild('routing_key', 'company.frontend');
                $xmlMessage->addChild('crud_operation', 'update');
                $xmlMessage->addChild('id', $masterUuid);
                $xmlMessage->addChild('name', $company->name);
                $xmlMessage->addChild('email', $company->email);
                $xmlMessage->addChild('telephone', $company->telephone);
                $xmlMessage->addChild('logo', $logo);

                $address = $xmlMessage->addChild('address');
                $address->addChild('country', $company->country);
                $address->addChild('state', $company->state);
                $address->addChild('city', $company->city);
                $address->addChild('zip', $company->zip);
                $address->addChild('street', $company->street);
                $address->addChild('house_number', $company->house_number);

                $xmlMessage->addChild('invoice', $company->invoice);
                $xmlMessage->addChild('source', 'frontend');
                $xmlMessage->addChild('company_id', $company->company_id);
                $xmlMessage->addChild('calendar_link', '');

                // Convert XML to string
                $message = $xmlMessage->asXML();

                \Log::info('XML message for company update: ' . $message); // Log the XML message
            } catch (\Exception $e) {
                \Log::error('Error creating XML message: ' . $e->getMessage()); // Log the error
                throw new \Exception('Error creating XML message: ' . $e->getMessage());
            }

            // Update Service ID
            try {
                $data_update = [
                    'MASTERUUID' => $masterUuid,
                    'Service' => 'frontend',
                    'NewServiceId' => $company->id,
                ];

                $response = $client->post('http://' . env('GENERAL_IP') . ':6000/updateServiceId', [
                    'json' => $data_update
                ]);

                \Log::info('Response from updateServiceId: ' . $response->getBody()); // Log the response
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                \Log::error('Error updating Service ID: ' . $e->getMessage()); // Log the error
                \Log::info($e->getMessage());
            }

            $routingKey = 'company.frontend';

            // Send message to RabbitMQ
            try {
                $this->sendMessageToTopic($routingKey, $message);
                \Log::info('Message sent to RabbitMQ: ' . $routingKey); // Log successful send
            } catch (\Exception $e) {
                \Log::error('Error sending message to RabbitMQ: ' . $e->getMessage()); // Log the error
                throw new \Exception('Error sending message to RabbitMQ: ' . $e->getMessage());
            }

            // Redirect back to the profile edit page with a success message
            return Redirect::route('company-profile.edit')->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            \Log::error('Error updating profile: ' . $e->getMessage()); // Log the error
            // Handle any exceptions and redirect back with an error message
            return Redirect::back()->withErrors(['error' => 'An error occurred while updating your profile. Please try again later.']);
        }
    }

    public function destroy(Request $request)
    {
        $request->validateWithBag('companyDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $company = Auth::guard('company')->user();

        // Create a new Guzzle HTTP client
        $client = new \GuzzleHttp\Client();

        // Define the data for the request
        $data = [
            'ServiceId' => $company->id,
            'Service' => 'frontend'
        ];

        $masterUuid = null;

        try {
            // Make the POST request
            $response = $client->request('POST', 'http://' . env('GENERAL_IP') . ':6000/getMasterUuid', [
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

        // Create XML message for company deletion
        $xmlMessage = new \SimpleXMLElement('<company/>');
        $xmlMessage->addChild('routing_key', 'company.frontend');
        $xmlMessage->addChild('crud_operation', 'delete');
        $xmlMessage->addChild('id', $masterUuid);
        $xmlMessage->addChild('name', $company->name);
        $xmlMessage->addChild('email', $company->email);
        $xmlMessage->addChild('telephone', $company->telephone);
        $xmlMessage->addChild('logo', $company->logo);

        $address = $xmlMessage->addChild('address');
        $address->addChild('country', $company->country);
        $address->addChild('state', $company->state);
        $address->addChild('city', $company->city);
        $address->addChild('zip', $company->zip);
        $address->addChild('street', $company->street);
        $address->addChild('house_number', $company->house_number);

        $xmlMessage->addChild('type', $company->type);
        $xmlMessage->addChild('invoice', $company->invoice);
        $xmlMessage->addChild('source', 'frontend');
        $xmlMessage->addChild('company_id', $company->company_id);
        $xmlMessage->addChild('calendar_link', '');

        try {
            $data_delete = [
                'MASTERUUID' => $masterUuid,
                'Service' => 'frontend',
                'NewServiceId' => null
            ];

            $response = $client->request('POST', 'http://' . env('GENERAL_IP') . ':6000/updateServiceId', [
                'json' => $data_delete
            ]);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle the exception
            echo $e->getMessage();
        }

        // Convert XML to string
        $message = $xmlMessage->asXML();

        // Send message to RabbitMQ
        $routingKey = 'company.frontend';

        try {
            $this->sendMessageToTopic($routingKey, $message);
        } catch (\Exception $e) {
            \Log::error('Error sending message to RabbitMQ: ' . $e->getMessage());
        }

        // Attempt to logout the company and delete the company record
        try {
            Auth::guard('web')->logout();
            $company->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            \Log::info('Company deleted successfully: ' . $company->id);

            return redirect()->route('user.home')->with('success', 'Company deleted successfully');
        } catch (\Exception $e) {
            \Log::error('Error deleting company: ' . $e->getMessage());
            return Redirect::back()->withErrors(['error' => 'An error occurred while deleting the company. Please try again later.']);
        }
    }

}
