<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validation
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Convert data to XML
        $xmlData = $this->arrayToXml($credentials);

        // Connect to RabbitMQ
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        // Declare exchange
        $exchange = 'login_exchange';
        $channel->exchange_declare($exchange, 'direct', false, true, false);

        // Publish login data to RabbitMQ
        $message = new AMQPMessage($xmlData);
        $channel->basic_publish($message, $exchange);

        // Close RabbitMQ connection
        $channel->close();
        $connection->close();

        // Attempt to log the user in
        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return redirect()->intended('/dashboard');
        }

        // Authentication failed...
        return redirect()->back()->withInput()->withErrors([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    // Helper function to convert array to XML
    private function arrayToXml($array, $rootElement = null, $xml = null)
    {
        $_xml = $xml;

        // If there is no root element, create it
        if ($_xml === null) {
            $_xml = new \SimpleXMLElement($rootElement !== null ? $rootElement : '<root/>');
        }

        // Loop through the array
        foreach ($array as $key => $value) {
            // If the key is numeric, prepend 'item'
            if (is_numeric($key)) {
                $key = "item{$key}";
            }

            // Add the key-value pair to the XML
            if (is_array($value)) {
                $this->arrayToXml($value, $key, $_xml->addChild($key));
            } else {
                $_xml->addChild($key, htmlspecialchars($value));
            }
        }

        return $_xml->asXML();
    }
}

