<!DOCTYPE html>
<html>
<head>
    <title>{{ __("BuWise") }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        .container {
            min-height: 100dvh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        button[type="submit"] {
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
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
