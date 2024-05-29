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
        width: 100%;
    }

    .styled-calendar-container {
        width: 100%; 
        height: 90vh; 
        border: 2px solid #4CAF50; 
        border-radius: 10px; 
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .styled-calendar-container iframe {
    width: 100%; 
    height: 100%; 
    border: none; 
    }

    .styled-calendar-container:hover {
        transform: scale(1.02); 
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); 
    }

</style>
</head>
<body>
@include('user.components.header')

<div class="container">
<center>
<iframe src="{{ $calendar_link }}" title="Styled Calendar" class="styled-calendar-container" style="width: 80vw; border: none;" data-cy="calendar-embed-iframe"></iframe>
<!--<script async type="module" src="https://embed.styledcalendar.com/assets/parent-window.js"></script>-->
    </center>


</div>

@include('user.components.footer')

</body>
</html>
