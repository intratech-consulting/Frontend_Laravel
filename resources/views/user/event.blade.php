<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evenementen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            margin: 0 auto;
            min-height: 100vh;
            padding: 20px;
        }

        .event-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .event-card img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .event-card h3 {
            margin-top: 15px;
            font-size: 18px;
            color: #333;
        }

        .event-card p {
            color: #666;
            margin-bottom: 10px;
        }

        .event-card .details-container {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .event-card .details-container img {
            margin-right: 5px;
            width: 20px;
            height: 20px;
        }

        .event-card .amount-text {
            margin-top: 10px;
            color: #888;
        }

        .event-card .buttons-container {
            margin-top: 15px;
        }

        .event-card .btn {
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .event-card .btn-ghost {
            border: 2px solid #333;
            color: #333;
            margin-right: 10px;
        }

        .event-card .btn-fill {
            background-color: #e30613;
            color: #fff;
        }

        .event-card .btn:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
@include('user.components.header')

<div class="container">
    <div class="row center-lg">
        @foreach($event as $events)
            <div class="col col-4">
                <div class="event-card">
                    <img src="#" alt="Event Image">
                    <h3>Event at {{$events->location}}</h3>
                    <p>{{$events->description}}</p>
                    <div class="details-container">
                        <img src="assets/img/check-square.svg" alt="tick" class="list-icon">
                        <p class="list-text">{{$events->max_registrations}} Registraties</p>
                    </div>
                    <div class="details-container">
                        <img src="assets/img/check-square.svg" alt="tick" class="list-icon">
                        <p class="list-text">{{$events->available_seats}} Beschikbare plaatsen</p>
                    </div>
                    <p class="amount-text">Gehost door {{$events->speaker_user_id->first_name}} van {{$events->speaker_company_id->name}}</p> 
                    <p class="amount-text">Datum: {{$events->date}}, Tijd: {{$events->start_time}} - {{$events->end_time}}</p>
                    <div class="buttons-container">
                        <a href="#" class="btn btn-ghost">Meer weergeven</a>
                        <form action="/events/register" method="POST">
                            @csrf 
                            <input type="hidden" name="event_id" value="{{$events->id}}"> 
                            <button type="submit" class="btn btn-fill">Registreren</button>
                        </form>

                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@include('user.components.footer')

</body>
</html>
