<?php

use PHPUnit\Framework\TestCase;
use App\Services\RabbitMQSendService;

class RabbitMQSendTest extends TestCase
{
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();

        // Instantiate RabbitMQSendService
        $this->service = new RabbitMQSendService();
    }

    public function testSendMessageToExistingQueue()
    {
        // Mock queue name and message
        $queueName = 'frontend';
        $message = 'This is a Frontend test';

        try {
            // Attempt to send message to the existing queue
            $result = $this->service->sendMessageToQueue($queueName, $message);

            // Assert that the message was sent successfully
            $this->assertTrue($result);
        } catch (\Exception $e) {
            // If an exception is caught, fail the test
            $this->fail("Failed to send message to queue: " . $e->getMessage());
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Close RabbitMQ connection
        $this->service->closeConnection();
    }
}
