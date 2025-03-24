<!DOCTYPE html>
<html>
<head>
    <title>{{ config('app.name') }}</title>
</head>
<body>
    @vite(['resources/css/auth/reset-password.css'])
    <form method="POST" action="/reset-password">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email">Email</label>
            <input id="email" name="email" value="{{ old('email', $request->email) }}" required autofocus />
            @error('email')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required />
            @error('password')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required />
            @error('password_confirmation')
                <p>{{ $message }}</p>
            @enderror
        </div>
        <div>
            <button type="submit">Reset Password</button>
        </div>
    </form>
</body>
</html>
