<?php

namespace App\Console\Command;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ProcessUserMessages extends Command
{

    protected $signature = 'user:process';
    protected $description = 'Process user messages from RabbitMQ';

    public function handle()
    {
        $connection = new AMQPStreamConnection('10.2.160.51', 5672, 'user', 'password');
        $channel = $connection->channel();

        $channel->queue_declare('frontend_test', false, true, false, false);

        $this->info(' [*] Waiting for messages. To exit press CTRL+C');
        echo "Waiting for messages. To exit press CTRL+C\n";

        $callback = function ($msg) {
            echo "Received message from RabbitMQ\n";

            $userData = simplexml_load_string($msg->body);

            // Perform validation
            $validator = Validator::make([
                'first_name' => (string) $userData->first_name,
                'last_name' => (string) $userData->last_name,
                'email' => (string) $userData->email,
                'telephone' => (string) $userData->telephone,
                'birthday' => (string) $userData->birthday,
                'country' => (string) $userData->address->country,
                'state' => (string) $userData->address->state,
                'city' => (string) $userData->address->city,
                'zip' => (string) $userData->address->zip,
                'street' => (string) $userData->address->street,
                'house_number' => (string) $userData->address->house_number,
                'invoice' => (string) $userData->invoice,
                'password' => '', // No password in the message
            ], [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
                'telephone' => ['required', 'string', 'max:20'],
                'birthday' => ['required', 'date'],
                'country' => ['required', 'string', 'max:255'],
                'state' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:255'],
                'zip' => ['required', 'string', 'max:20'],
                'street' => ['required', 'string', 'max:255'],
                'house_number' => ['required', 'string', 'max:20'],
                'invoice' => ['required'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                $this->error('Validation failed: ' . $validator->errors()->first());
                $msg->ack(); // Acknowledge message
                echo "Validation failed. Message discarded.\n";
                return;
            }

            echo "Message validated successfully\n";

            // Check if the message is for user object
            if ((string)$userData->routing_key !== 'user.crm') {
                // If it's not a user object, acknowledge and skip processing
                $this->info(' [x] Message is not a user object. Skipping...');
                $msg->ack();
                echo "Message is not a user object. Skipping...\n";
                return;
            }

            echo "Message is for a user object\n";

            // Check if the message is for user creation or update
            if ((string)$userData->crud_operation === 'create') {
                // Create new user
                $user = new User();
                echo "Creating new user\n";
            } elseif ((string)$userData->crud_operation === 'update') {
                // Find existing user by email
                $user = User::where('email', (string)$userData->email)->first();
                if (!$user) {
                    // If user does not exist, skip processing
                    $this->error(' [x] User with email ' . $userData->email . ' not found.');
                    $msg->ack();
                    echo "User with email {$userData->email} not found. Skipping...\n";
                    return;
                }
                echo "Updating user\n";
            } else {
                // Unsupported operation, skip processing
                $this->error(' [x] Unsupported CRUD operation: ' . $userData->crud_operation);
                $msg->ack();
                echo "Unsupported CRUD operation: {$userData->crud_operation}. Skipping...\n";
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
            echo "User {$userData->email} processed successfully\n";
            $msg->ack();
            echo "Message acknowledged\n";
        };

        $channel->basic_consume('frontend_test', '', false, false, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
            echo "Waiting for next message...\n";
        }

        $channel->close();
        $connection->close();
    }
}
