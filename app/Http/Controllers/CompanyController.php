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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;



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

            Validator::extend('unique_across_tables', function ($attribute, $value, $parameters, $validator) {
                $companiesCount = DB::table('companies')->where('email', $value)->count();
                $usersCount = DB::table('users')->where('email', $value)->count();

                return $companiesCount + $usersCount === 0;
            });

            $companyData = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'unique_across_tables', 'string', 'email', 'max:255'],
                'telephone' => ['required', 'string', 'max:20'],
                'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg,webp', 'max:2048'],
                'country' => ['required', 'string', 'max:255'],
                'state' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:255'],
                'zip' => ['required', 'string', 'max:20'],
                'street' => ['required', 'string', 'max:255'],
                'house_number' => ['required', 'string', 'max:20'],
                'invoice' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
        try {
            $logoPath = $request->hasFile('logo') ? $request->file('logo')->store('logos', 'public') : null;

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
                'sponsor' => $request->has('sponsor') ? true : false,
                'invoice' => $companyData['invoice'],
                'password' => Hash::make($companyData['password']),
            ]);

            // Create a new Guzzle HTTP client
            $client = new \GuzzleHttp\Client();
            $data = [
                'Service' => 'frontend',
                'ServiceId' => $company->id,
            ];

            try {
                $response = $client->post('http://' . env('GENERAL_IP') . ':6000/createMasterUuid', ['json' => $data]);
                $body = $response->getBody();
                \Log::info('UUID Response Body: ' . $body);
                $json = json_decode($body, true);
                $masterUuid = $json['MasterUuid'];
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                $this->rabbitMQService->sendLogEntryToTopic('make_UUID', 'Error: ' . $e->getMessage(), true);
                throw new \Exception('Failed to retrieve masterUuid: ' . $e->getMessage());
            }

            if (!isset($masterUuid)) {
                throw new \Exception('Master UUID is not set. Message will not be sent to RabbitMQ.');
            }

            $xmlCompany = new \SimpleXMLElement('<company/>');
            $xmlCompany->addChild('routing_key', 'company.frontend');
            $xmlCompany->addChild('crud_operation', 'create');
            $xmlCompany->addChild('id', $masterUuid);
            $xmlCompany->addChild('name', $this->convertToUtf8($companyData['name']));
            $xmlCompany->addChild('email', $this->convertToUtf8($companyData['email']));
            $xmlCompany->addChild('telephone', $this->convertToUtf8($companyData['telephone']));
            $xmlCompany->addChild('logo', $logoPath ?? '');

            $address = $xmlCompany->addChild('address');
            $address->addChild('country', $this->convertToUtf8($companyData['country']));
            $address->addChild('state', $this->convertToUtf8($companyData['state']));
            $address->addChild('city', $this->convertToUtf8($companyData['city']));
            $address->addChild('zip', $this->convertToUtf8($companyData['zip']));
            $address->addChild('street', $this->convertToUtf8($companyData['street']));
            $address->addChild('house_number', $this->convertToUtf8($companyData['house_number']));

            $xmlCompany->addChild('sponsor', $request->has('sponsor') ? 'true' : 'false');
            $xmlCompany->addChild('invoice', $this->convertToUtf8($companyData['invoice']));

            $message = $xmlCompany->asXML();
            $routingKey = 'company.frontend';

            $this->sendMessageToTopic($routingKey, $message);

            $this->rabbitMQService->sendLogEntryToTopic('create company', 'Company (masterUuid: ' . $masterUuid . ', name: ' . $companyData['name'] . ') created successfully', false);

            return redirect()->route('user.home')->with('success', 'Je bedrijf is succesvol aangemaakt ' . $company->name . '!');
        } catch (\Exception $e) {
            $this->rabbitMQService->sendLogEntryToTopic('create company', 'Error: [Company (name: ' . $companyData['name'] . ') created unsuccessfully] -> ' . $e->getMessage(), true);
            return Redirect::back()->withErrors('failed', 'Je bedrijf  is niet succesvol aangemaakt!');
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

        Validator::extend('unique_across_tables', function ($attribute, $value, $parameters, $validator) use ($company) {
            $companiesCount = DB::table('companies')->where('email', $value)->where('id', '!=', $company->id)->count();
            $usersCount = DB::table('users')->where('email', $value)->count();

            return $companiesCount + $usersCount === 0;
        });

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'unique_across_tables', 'string', 'email', 'max:255'],
            'telephone' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'zip' => ['required', 'string', 'max:20'],
            'street' => ['required', 'string', 'max:255'],
            'house_number' => ['required', 'string', 'max:20'],
            'invoice' => ['required', 'string', 'max:255'],
        ]);


        $companyData = [
            'name' => $request->name,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'zip' => $request->zip,
            'street' => $request->street,
            'house_number' => $request->house_number,
            'invoice' => $request->invoice,
        ];

        $company->update($companyData);

            // Retrieve the authenticated company
            $company = Auth::guard('company')->user();

            \Log::info('Authenticated company: ' . json_encode($company)); // Log the authenticated company

            // Store the old email for comparison
            $oldEmail = $company->email;
        try{
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
            $this->rabbitMQService->sendLogEntryToTopic('update company', 'Company (id: ' . $company->id .  ', name: ' . $company->name . ' updated successfully', false);

            // Redirect back to the profile edit page with a success message
            return Redirect::route('company-profile.edit')->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            \Log::error('Error updating profile: ' . $e->getMessage()); // Log the error

            //send log
            $this->rabbitMQService->sendLogEntryToTopic('update company', 'Error: [Company (id: ' . $company->id .   ', name: ' . $company->name . ' updated unsuccessfully] -> ' . $e->getMessage(), true);

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

        $xmlMessage->addChild('sponsor', $this->convertToUtf8($company->sponsor));
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
