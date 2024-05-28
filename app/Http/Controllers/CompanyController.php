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

    public function logout(Request $request)
    {
        Auth::guard('company')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.home');
    }

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

    private function convertToUtf8($value)
    {
        return mb_convert_encoding($value, 'UTF-8', 'auto');
    }

    public function create_company(Request $request)
    {
        try{
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
            'sponsor' => ['nullable', 'boolean'],
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
            'sponsor' => $companyData['sponsor'] ?? false,
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
        $xmlCompany->addChild('id', $masterUuid);
        $xmlCompany->addChild('name', $this->convertToUtf8($companyData['name']));
        $xmlCompany->addChild('email', $this->convertToUtf8($companyData['email']));
        $xmlCompany->addChild('telephone', $this->convertToUtf8($companyData['telephone']));
        $xmlCompany->addChild('logo', $logo);

        $address = $xmlCompany->addChild('address');
        $address->addChild('country', $this->convertToUtf8($companyData['country']));
        $address->addChild('state', $this->convertToUtf8($companyData['state']));
        $address->addChild('city', $this->convertToUtf8($companyData['city']));
        $address->addChild('zip', $this->convertToUtf8($companyData['zip']));
        $address->addChild('street', $this->convertToUtf8($companyData['street']));
        $address->addChild('house_number', $this->convertToUtf8($companyData['house_number']));

        $xmlCompany->addChild('sponsor', $this->convertToUtf8($companyData['sponsor']));
        $xmlCompany->addChild('invoice', $this->convertToUtf8($companyData['invoice']));


        // Convert XML to string
        $message = $xmlCompany->asXML();
        // Send message to RabbitMQ
        $routingKey = 'company.frontend';

        $this->sendMessageToTopic($routingKey, $message);

        event(new Registered($company));

        Auth::login($company);

        //send log
        $this->rabbitMQService->sendLogEntryToTopic('create company', 'Company (masterUuid: ' . $masterUuid .  ', name: ' . $companyData['name'] . 'created successfully', false);

        return redirect()
            ->route('user.home')
            ->with('success', 'Je bedrijf is succesvol aangemaakt  ' . $company->name . '!');
        }
        catch(\Exception $e)
        {
        //send log
        $this->rabbitMQService->sendLogEntryToTopic('create company', 'Error: [Company (name: ' . $companyData['name'] . 'created unsuccessfully] -> ' . $e->getMessage(), true);

        // Handle the exception
        throw new \Exception('failed', 'Je bedrijf is niet succesvol aangemaakt ' . $companyData['name'] . '!' . $e->getMessage());
        }
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
            'country' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'zip' => 'required|string|max:10',
            'street' => 'required|string|max:100',
            'house_number' => 'required|string|max:10',
            'invoice' => 'required|string|max:34',
        ]);

        // Update the other attributes from the request
        $company->update($request->all());

        // Handle file upload
        if ($request->hasFile('logo')) {
            // Validate the logo separately
            $request->validate([
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            ]);

            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }

            $logoPath = $request->file('logo')->store('logos', 'public');
            $company->logo = $logoPath;

            // If a new logo was uploaded, save the model again to store the new logo path
            $company->save();
        }

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
                $xmlMessage->addChild('name', $this->convertToUtf8($company->name));
                $xmlMessage->addChild('email', $this->convertToUtf8($company->email));
                $xmlMessage->addChild('telephone', $this->convertToUtf8($company->telephone));
                $xmlMessage->addChild('logo', $company->logo);

                $address = $xmlMessage->addChild('address');
                $address->addChild('country', $this->convertToUtf8($company->country));
                $address->addChild('state', $this->convertToUtf8($company->state));
                $address->addChild('city', $this->convertToUtf8($company->city));
                $address->addChild('zip', $this->convertToUtf8($company->zip));
                $address->addChild('street', $this->convertToUtf8($company->street));
                $address->addChild('house_number', $this->convertToUtf8($company->house_number));

                $xmlMessage->addChild('invoice', $this->convertToUtf8($company->invoice));
                $xmlMessage->addChild('source', 'frontend');
                $xmlMessage->addChild('company_id', $this->convertToUtf8($company->company_id));
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

            //send log
            $this->rabbitMQService->sendLogEntryToTopic('update company', 'Company (masterUuid: ' . $masterUuid .  ', name: ' . $company->name . ' updated successfully', false);

            // Redirect back to the profile edit page with a success message
            return Redirect::route('company-profile.edit')->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            \Log::error('Error updating profile: ' . $e->getMessage()); // Log the error

            //send log
            $this->rabbitMQService->sendLogEntryToTopic('update company', 'Error: [Company (masterUuid: ' . $masterUuid .  ', name: ' . $company->name . ' updated unsuccessfully] -> ' . $e->getMessage(), true);

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
        $xmlMessage->addChild('name', $this->convertToUtf8($company->name));
        $xmlMessage->addChild('email', $this->convertToUtf8($company->email));
        $xmlMessage->addChild('telephone', $this->convertToUtf8($company->telephone));
        $xmlMessage->addChild('logo', $company->logo);

        $address = $xmlMessage->addChild('address');
        $address->addChild('country', $this->convertToUtf8($company->country));
        $address->addChild('state', $this->convertToUtf8($company->state));
        $address->addChild('city', $this->convertToUtf8($company->city));
        $address->addChild('zip', $this->convertToUtf8($company->zip));
        $address->addChild('street', $this->convertToUtf8($company->street));
        $address->addChild('house_number', $this->convertToUtf8($company->house_number));

        $xmlCompany->addChild('sponsor', $this->convertToUtf8($company->sponsor));
        $xmlMessage->addChild('invoice', $this->convertToUtf8($company->invoice));
        $xmlMessage->addChild('source', 'frontend');
        $xmlMessage->addChild('company_id', $this->convertToUtf8($company->company_id));
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

        // Deleting the company record and logging out
        try {
            // Attempt to delete the company record
            $company->delete();

            // Log out the company using the logout method
            $this->logout($request);

            //send log
            $this->rabbitMQService->sendLogEntryToTopic('delete company', 'Company (masterUuid: ' . $masterUuid .  ', name: ' . $company->name . ' deleted successfully', false);

            // Redirect to the login page with a success message
            return redirect()->route('login')->with('success', 'Company deleted and logged out successfully');
        } catch (\Exception $e) {
            \Log::error('Error deleting company: ' . $e->getMessage());

            //send log
            $this->rabbitMQService->sendLogEntryToTopic('delete company', 'Error: [Company (masterUuid: ' . $masterUuid .  ', name: ' . $company->name . ' deleted unsuccessfully] -> ' . $e->getMessage(), true);

            return Redirect::back()->withErrors(['error' => 'An error occurred while deleting the company. Please try again later.']);
        }
    }

}
