<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration</title>
</head>
<body>
<h1>Register for an Event</h1>

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

<form action="{{ route('register') }}" method="POST">
    @csrf
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required><br><br>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required><br><br>

    <label for="event">Event:</label>
    <select name="event_id" id="event" required>
        @foreach($events as $event)
            <option value="{{ $event->id }}">{{ $event->name }} - {{ $event->date }}</option>
        @endforeach
    </select><br><br>

    <button type="submit">Register</button>
</form>
</body>
</html>
