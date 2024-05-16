<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RabbitMQSendToExhangeService;
use App\Models\Company;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



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
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,bmp,gif,svg,webp', 'max:2048'],// Max file size in kilobytes (2MB)
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

        $companyId = $company->id;

        $xmlCompany = new \SimpleXMLElement('<company/>');
        $xmlCompany->addChild('routing_key', 'company.frontend');
        $xmlCompany->addChild('crud_operation', 'create');

        $xmlCompany->addChild('id', $companyId); // Generate a random ID
        $xmlCompany->addChild('name', $companyData['name']);
        $xmlCompany->addChild('email', $companyData['email']);
        $xmlCompany->addChild('telephone', $companyData['telephone']);
        $xmlCompany->addChild('logo', $companyData['logo']);

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

        event(new Registered($company));

        Auth::login($company);

        return redirect(route('headerController.home'));
    }
}
