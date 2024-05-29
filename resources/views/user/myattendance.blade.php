<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn reservaties</title>

<style>
        .container {
            min-height: 100vh;
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .reservation-card {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .button {
            all: unset;
            background-color: #e30613;
            border-radius: 3px;
            padding: 8px 12px;
            cursor: pointer;
            border: none;
            color: white;
        }

        button:hover {
            background-color: #25b4b1;
            color: #fff;
        }
</style>
</head>
<body>
@include('user.components.header')

<div class="container">

<h1>My Reservations</h1>
    @foreach($attendances as $attendance)
        @php
            $event = $events->firstWhere('id', $attendance->event_id);
        @endphp
        @if($event)
            <div class="reservation-card">
                <h2>Event bij {{$event->location}}</h2>
                <p>{{$event->description}}</p>
                <p>Gehost door {{$event->users->first_name}} van {{$event->speaker_company_id}}</p>
                <p>Datum: {{$event->date}}, Tijd: {{$event->start_time}} - {{$event->end_time}}</p>
                <form action="{{ route('events.unsubscribe') }}" method="POST">
                    @csrf
                        <input type="hidden" name="attendances_id" value="{{$attendance->id}}">
                        <input type="hidden" name="event_id" value="{{$event->id}}">
                        <button class="button">Uitschrijven</button>
                </form>
            </div>
        @endif
    @endforeach

</div>

@include('user.components.footer')

</body>
</html>
