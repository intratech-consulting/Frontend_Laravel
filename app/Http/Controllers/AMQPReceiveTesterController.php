<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RabbitMQReceiveService;

class AMQPReceiveTesterController extends Controller
{
    protected $rabbitMQService;

    public function __construct(RabbitMQReceiveService $rabbitMQService)
    {
        $this->rabbitMQService = $rabbitMQService;
    }

    public function receiveMessages($queueName)
    {
        try {
            // Receive messages from RabbitMQ queue using injected service
            $messages = $this->rabbitMQService->receiveMessagesFromQueue($queueName);

            // Process each message
            foreach ($messages as $message) {
                // Process the message here (e.g., store in database, log, etc.)
                // For demonstration, we'll just log the message
                \Log::info('Received message from RabbitMQ: ' . $message);
            }

            return response()->json(['status' => 'Messages received successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during message receiving
            return response()->json(['error' => 'Failed to receive messages'], 500);
        }
    }

    public function processMessages(Request $request)
    {
        $queueName = 'frontend'; // Assuming you want to receive messages from the 'frontend' queue

        // Call receiveMessages method to receive the messages
        return $this->receiveMessages($queueName);
    }
}
