<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProcessUserMessages extends Command
{
    protected $signature = 'user:process';
    protected $description = 'Process user messages from RabbitMQ';

    public function handle()
    {
        $connection = new AMQPStreamConnection('10.2.160.51', 5672, 'user', 'password');
        $channel = $connection->channel();

        $channel->queue_declare('user_queue', false, true, false, false);

        $this->info(' [*] Waiting for messages. To exit press CTRL+C');

        $callback = function ($msg) {
            $userData = simplexml_load_string($msg->body);

            // Check if the message is for user object
            if ((string)$userData->routing_key !== 'user.crm') {
                // If it's not a user object, acknowledge and skip processing
                $this->info(' [x] Message is not a user object. Skipping...');
                $msg->ack();
                return;
            }

            // Check if the message is for user creation or update
            if ((string)$userData->crud_operation === 'create') {
                // Create new user
                $user = new User();
            } elseif ((string)$userData->crud_operation === 'update') {
                // Find existing user by email
                $user = User::where('email', (string)$userData->email)->first();
                if (!$user) {
                    // If user does not exist, skip processing
                    $this->error(' [x] User with email ' . $userData->email . ' not found.');
                    $msg->ack();
                    return;
                }
            } else {
                // Unsupported operation, skip processing
                $this->error(' [x] Unsupported CRUD operation: ' . $userData->crud_operation);
                $msg->ack();
                return;
            }

            // Populate user data
            $user->first_name = (string)$userData->first_name;
            $user->last_name = (string)$userData->last_name;
            $user->email = (string)$userData->email;
            $user->telephone = (string)$userData->telephone;
            $user->birthday = (string)$userData->birthday;
            $user->country = (string)$userData->address->country;
            $user->state = (string)$userData->address->state;
            $user->city = (string)$userData->address->city;
            $user->zip = (string)$userData->address->zip;
            $user->street = (string)$userData->address->street;
            $user->house_number = (string)$userData->address->house_number;
            $user->company_email = (string)$userData->company_email ?? null;
            $user->company_id = (string)$userData->company_id ?? null;
            $user->source = (string)$userData->source;
            $user->user_role = (string)$userData->user_role;
            $user->invoice = (string)$userData->invoice;
            $user->calendar_link = (string)$userData->calendar_link ?? null;

            // Save user to database
            $user->save();

            $this->info(' [x] User ' . $userData->email . ' processed.');
            $msg->ack();
        };

        $channel->basic_consume('frontend_test', '', false, false, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}
