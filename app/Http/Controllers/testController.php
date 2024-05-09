<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RabbitMQSendService;

class testController extends Controller
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

    public function test(Request $request)
    {
        if ($request->isMethod('get'))
        {
            return view('test');
        }
        
        $queueName = 'frontend';

                // Validate the message
        $request->validate([
            'message' => 'required|string',
        ]);

            // Extract the message from the request
        $message = $request->input('message');
    
            // Call sendMessage method to send the message
         $this->sendMessage($queueName, $message);

    }
}
