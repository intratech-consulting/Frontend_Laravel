<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evenementen</title>
</head>
<body>
@include('user.components.header')

                <div class="row center-lg">


@foreach($event as $events)
    <div class="rooms col col-2">
        <img style="height:300px !important" width="400px" src="#" alt="" class="rooms-img">
        <h3 class="room-title">Event at {{$events->location}}</h3>
        <p class="room-text">{{$events->description}}</p>
        <div>
            <div class="details-container">
                <img src="assets/img/check-square.svg" alt="tick" class="list-icon">
                <p class="list-text">{{$events->max_registrations}} Registraties</p>
            </div>
            <div class="details-container">
                <img src="assets/img/check-square.svg" alt="tick" class="list-icon">
                <p class="list-text">{{$events->available_seats}} Beschikbare plaatsen</p>
            </div>
        </div>
        <p class="amount-text">Gehost door {{$events->speaker_user_id}} van {{$events->speaker_company_id}}</p>
        <p class="amount-text">Datum: {{$events->date}}, Tijd: {{$events->start_time}} - {{$events->end_time}}</p>
        <div class="buttons-container">
            <a href="#" class="btn btn-ghost">Meer weergeven</a>
            <a href="#" class="btn btn-fill">Registreren</a>
        </div>
    </div>
@endforeach


                </div>


@include('user.components.footer')


</body>
</html>
