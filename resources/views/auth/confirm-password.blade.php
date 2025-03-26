<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
@vite(['resources/css/auth/confirm-password.css'])
<body>
    <div class="container">
        <h4>
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </h4>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf
            <!-- Password -->
            <div>
                <label for="password">{{ "Password" }}</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" />
            </div>
            @error('password')
                <p>{{ $message }}</p>
            @enderror
            <button type="submit">Confirm</button>
        </form>
    </div>
</body>
</html>
