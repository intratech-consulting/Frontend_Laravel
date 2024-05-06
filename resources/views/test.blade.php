<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Send Message to RabbitMQ</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Send Message to RabbitMQ</h2>
    
    <form id="sendMessageForm">
        <label for="message">Message:</label><br>
        <textarea id="message" name="message" rows="4" cols="50"></textarea><br><br>
        <button type="submit">Send Message</button>
    </form>

    <div id="responseMessage"></div>

    <script>
        $(document).ready(function() {
            $('#sendMessageForm').submit(function(event) {
                event.preventDefault(); // Prevent the default form submit action

                // Get the message from the textarea
                var message = $('#message').val();

                // Make AJAX POST request to the Laravel endpoint
                $.ajax({
                    url: '/send-message', // Assuming this is your Laravel route
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), // Include CSRF token
                        'Content-Type': 'application/json' // Include content type
                    },
                    contentType: 'application/json',
                    data: JSON.stringify({ message: message }),
                    success: function(response) {
                        // Display success message
                        $('#responseMessage').text(response.status).css('color', 'green');
                    },
                    error: function(xhr, status, error) {
                        // Display error message
                        $('#responseMessage').text('Failed to send message').css('color', 'red');
                    }
                });
            });
        });
    </script>
</body>
</html>
