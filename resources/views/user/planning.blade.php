<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>planning</title>
</head>
<body>
@include('user.components.header')

<div>
<center>
<iframe src="{{ $calendar_link }}" style="border: 0" width="80%" height="600" frameborder="0" scrolling="no"></iframe>
    </center>


@include('user.components.footer')

</body>
</html>
