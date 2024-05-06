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

    public function sendMessage(Request $request)
    {
        $queueName = 'frontend'; // Specify the queue name
        $message = $request->input('message'); // Get the message from the request

        // Validate the message (optional)
        $request->validate([
            'message' => 'required|string',
        ]);

        try {
            // Send the message to RabbitMQ queue
            $this->rabbitMQService->sendMessageToQueue($queueName, $message);

            return response()->json(['status' => 'Message sent successfully'], 200);
        } catch (\Exception $e) {
            \Log::error('Failed to send message: ' . $e->getMessage());
            // Handle any exceptions that occur during message sending
            return response()->json(['error' => 'Failed to send message'], 500);
        }
    }

    public function test()
    {
        return view('test');
    }
}
