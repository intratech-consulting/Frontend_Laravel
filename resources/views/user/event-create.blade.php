<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea,
        .form-group select {
            width: calc(100% - 10px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }

        .form-group textarea {
            resize: vertical;
        }

        .form-group select {
            width: 100%;
        }

        .form-group button[type="submit"] {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-group button[type="submit"]:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    @include('user.components.header')

    <div class="container">
        <h2>Create Event</h2>
        <form method="POST" action="{{ route('create_event') }}">
            @csrf

            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="max_registrations">Max Registrations:</label>
                <input type="number" id="max_registrations" name="max_registrations" required>
            </div>

            <div class="form-group">
                <label for="available_seats">Available Seats:</label>
                <input type="number" id="available_seats" name="available_seats" required>
            </div>

            <div class="form-group">
                <label for="speaker_name">Speaker Name:</label>
                <input type="text" id="speaker_name" name="speaker_name" required>
            </div>

            <div class="form-group">
                <label for="speaker_company">Speaker Company:</label>
                <input type="text" id="speaker_company" name="speaker_company" required>
            </div>

            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>
            </div>

            <div class="form-group">
                <label for="start_time">Start Time:</label>
                <input type="time" id="start_time" name="start_time" required>
            </div>

            <div class="form-group">
                <label for="end_time">End Time:</label>
                <input type="time" id="end_time" name="end_time" required>
            </div>

            <div class="form-group">
                <button type="submit">Create Event</button>
            </div>
        </form>
    </div>

    @include('user.components.footer')
</body>
</html>
