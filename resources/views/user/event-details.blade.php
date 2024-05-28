<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->name }}</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
        }

        .container {
            flex: 1;
            margin: 20px 80px 20px 80px;
            max-width: 1200px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .event-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .event-header h1 {
            font-size: 24px;
            color: #333;
        }

        .event-header p {
            color: #666;
        }

        .event-details img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .event-details p {
            color: #666;
            margin: 10px 0;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
            background-color: #e30613;
            color: #fff;
            text-align: center;
        }

        .btn:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

@include('user.components.header')

<div class="container">
    <div class="event-header">
        <h1>{{ $event->name }}</h1>
        <p>Gehost door:  {{ $event->users->first_name }} van {{ $event->companies->name }}</p>
    </div>
    <div class="event-details">
        <img src="#" alt="Event Image">
        <p>{{ $event->description }}</p>
        <p>Datum: {{ $event->date }}</p>
        <p>Tijd: {{ $event->start_time }} - {{ $event->end_time }}</p>
        <p>Locatie: {{ $event->location }}</p>
        <p>{{ $event->max_registrations }} Registraties</p>
        <p>{{ $event->available_seats }} Beschikbare plaatsen</p>
    </div>
</div>

@include('user.components.footer')

</body>
</html>
