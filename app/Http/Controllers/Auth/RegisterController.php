<?php
 
namespace App\Http\Controllers\Auth;
 
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
 
class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        dd('test');
        return view('register');
    }
 
    public function register(Request $request)
    {
        // Validation
        $userData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => 'nullable|string|max:20',
            'birthday' => 'nullable|date',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:20',
            'street' => 'nullable|string|max:255',
            'house_number' => 'nullable|string|max:20',
            'company_email' => 'nullable|string|email|max:255',
            'company_id' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'user_role' => 'required|string|in:Speaker,Individual,Employee',
            'invoice' => 'required|string|in:Yes,No',
            'password' => 'required|string|min:8|confirmed',
        ]);
 
        // Create the user
        $user = User::create([
            'name' => $userData['first_name'] . ' ' . $userData['last_name'],
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
            'company_email' => $userData['company_email'],
            'company_id' => $userData['company_id'],
            'source' => $userData['source'],
            'user_role' => $userData['user_role'],
            'invoice' => $userData['invoice'],
        ]);
 
        // Convert data to XML
        $xmlData = $this->arrayToXml($userData);
 
        // Connect to RabbitMQ
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
 
        // Declare exchange
        $exchange = 'register_exchange';
        $channel->exchange_declare($exchange, 'direct', false, true, false);
 
        // Publish registration data to RabbitMQ
        $message = new AMQPMessage($xmlData);
        $channel->basic_publish($message, $exchange);
 
        // Close RabbitMQ connection
        $channel->close();
        $connection->close();
 
        // Redirect after registration
        return redirect('/home'); // Adjust this to your desired redirect path
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
