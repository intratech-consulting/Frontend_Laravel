<?php
 
namespace App\Http\Controllers\Auth;
 
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use App\Services\RabbitMQSendService;

 
class RegisterController extends Controller
{
    protected $rabbitMQService;

    public function __construct(RabbitMQSendService $rabbitMQService)
    {
        $this->rabbitMQService = $rabbitMQService;
    }

    public function sendMessage($queueName, $message)
    {
        try {
            // Send the message to RabbitMQ queue using injected service
            $this->rabbitMQService->sendMessageToQueue($queueName, $message);

            return response()->json(['status' => 'Message sent successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during message sending
            return response()->json(['error' => 'Failed to send message'], 500);
        }
    }

    public function showRegistrationForm()
    {
        dd('test');
        return view('auth.register');
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

        $queueName = 'frontend';
 
        // Convert data to XML
        $message = $this->arrayToXml($userData);

        dd('message');
        
        $this->sendMessage($queueName, $message);
        // Redirect after registration
        return redirect(url('/')); // Adjust this to your desired redirect path
    }    
}
