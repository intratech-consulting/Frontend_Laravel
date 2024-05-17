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
}

</style>
</head>
<body>
@include('user.components.header')

<div class="container">
<div class="main">
<center>
<iframe src="https://embed.styledcalendar.com/#RNRQ7GYT8sv7grrj2Wlv" title="Styled Calendar" class="styled-calendar-container" style="width: 80%; border: none;" data-cy="calendar-embed-iframe"></iframe>
<script async type="module" src="https://embed.styledcalendar.com/assets/parent-window.js"></script>
    </center>


@include('user.components.footer')

</div>
</body>
</html>
