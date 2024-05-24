<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>planning</title>

<style>
.container {
        margin: 0 auto;
        min-height: 100vh;
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .styled-calendar-container {
        width: 90%;
        height: 80vh; /* Make the calendar take 80% of the viewport height */
        border: 2px solid #4CAF50; /* Add a green border */
        border-radius: 10px; /* Round the corners */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add a subtle shadow */
        transition: transform 0.3s ease; /* Add a smooth transition for scaling */
    }

    .styled-calendar-container:hover {
        transform: scale(1.02); /* Slightly enlarge the calendar on hover */
    }



</style>
</head>
<body>
@include('user.components.header')

<div class="container">
<center>
<iframe src="{{ $calendar_link }}" title="Styled Calendar" class="styled-calendar-container" style="width: 80%; border: none;" data-cy="calendar-embed-iframe"></iframe>
<!--<script async type="module" src="https://embed.styledcalendar.com/assets/parent-window.js"></script>-->
    </center>


</div>

@include('user.components.footer')

</body>
</html>
