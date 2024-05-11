<!-- resources/views/test.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Send Message Form</title>
</head>
<body>
    <h2>Send Message to Queue</h2>
    <form method="POST" action="{{route('test')}}">
        @csrf
        <label for="message">Message:</label><br>
        <textarea id="message" name="message" rows="4" cols="50" required></textarea><br><br>
        <button type="submit">Send Message</button>
    </form>
</body>
</html>
