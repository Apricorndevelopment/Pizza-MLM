
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h2>Welcome, {{ $user->name }}!</h2>

    <p>You have registered successfully.</p>

    <p><strong>Your Login Details:</strong></p>

    <ul>
        <li>Username: {{ $user->ulid }}</li>
        <li>Email: {{ $user->email }}</li>
        <li>Password: {{ $plainPassword }}</li>
    </ul>

    <p>Please login here: <a href="{{ route('auth.login') }}">{{ route('auth.login') }}</a></p>

    <p>Thank you,<br>The Team</p>

</body>

</html>
