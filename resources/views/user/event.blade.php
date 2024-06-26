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
            position: relative;

        }

        .event-card img {
            width: 100%;
            height: 200px
            object-fit: cover;
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
            padding-bottom: 10px;
        }

        .event-card .buttons-container {
            margin-top: 15px;
            position: relative;
            bottom: 20px;
            left: 20px;
        }

        .event-card .btn {
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block; /*added*/
        }

        .event-card .btn-ghost {
            border: 2px solid #333;
            color: #333;
            margin-right: 10px;
            float: left; /*added */
        }

        .event-card .btn-fill {
            background-color: #e30613;
            color: #fff;
        }

        .event-card .btn:hover {
            transform: translateY(-2px);
        }
        .guest-message {
        color: red;
        font-weight: bold;

        }

    .guest-message a {
        color: white;
        background-color: red;
        border: 2px solid #333;
        padding: 8px 12px;
        text-decoration: none;
        border-radius: 4px;
        margin-left: 10px;
        float: left;
        transition: transform 0.2s;
    }
    .guest-message a:hover {
    transform: translateY(-2px);
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

    a{
        color: #fff;
        text-decoration: none;
        font-weight: 500;
        padding: 8px 12px;
        border-radius: 3px;
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
                    <img src="https://images.unsplash.com/photo-1560439514-4e9645039924?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Event Image">
                    <h3>{{$events->title}}</h3>
                    <p>Event bij {{$events->location}}</p>
                    <p>{{$events->description}}</p> <!-- to be in details-->
                    <div class="details-container">
                        <p class="list-text">{{$events->max_registrations}} Registraties</p>
                    </div>
                    <div class="details-container">
                        <p class="list-text">{{$events->available_seats}} Beschikbare plaatsen</p>
                    </div>
                    <p class="amount-text">Gehost door {{$events->users->first_name}} van {{$events->companies->name}}</p>
                    <p class="amount-text">Datum: {{$events->date}}, Tijd: {{$events->start_time}} - {{$events->end_time}}</p>

                    @auth('web')
                        <form action="{{ route('events.register') }}" method="POST">
                            @csrf
                            <input type="hidden" name="event_id" value="{{$events->id}}">
                            <button type="submit" class="button">Inschrijven</button>
                        </form>
                    @elseauth('company')
                    @else
                        <button type="button" class="button" onclick="window.location.href='{{ route('login') }}'">log in to register</button>
                    @endauth


                </div>
            </div>
        @endforeach
    </div>
</div>

@include('user.components.footer')

</body>
</html>
