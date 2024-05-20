<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @include('user.components.header')
    <div class="container">
        <h1>Events</h1>
        <div class="row">
            @foreach ($events as $event)
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">{{ $event->location }}</h5>
                            <p class="card-text">
                                Date: {{ $event->date }}<br>
                                Start Time: {{ $event->start_time }}<br>
                                End Time: {{ $event->end_time }}<br>
                                Speaker User ID: {{ $event->speaker_user_id }}<br>
                                Speaker Company ID: {{ $event->speaker_company_id }}<br>
                                Max Registrations: {{ $event->max_registrations }}<br>
                                Available Seats: {{ $event->available_seats }}<br>
                                Description: {{ $event->description }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    @include('user.components.footer')

    
</body>
</html>