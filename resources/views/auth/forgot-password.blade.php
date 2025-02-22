<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuWise</title>
    <style>
        * {
            margin: 0;
            box-sizing: border-box;
        }
        .reset-password-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100dvh;
            background-color: #1e2a3a;
        }

        .reset-password-box {
            background: #2c3e50;
            padding: 40px;
            border-radius: 8px;
            text-align: center;
            width: 350px;
        }

        h2 {
            color: white;
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            color: white;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .button-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn-send {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            display: block;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-back {
            background-color: #7d7d7d;
            color: white;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            display: block;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="reset-password-container">
        <div class="reset-password-box">
            <h2>Forgot Password?</h2>
            <p>Enter Email</p>

            <!-- Status of request e.g. "reset link sent" or "please wait before retrying" -->
            <p>{{ session("status") }}</p>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="input-group">
                    <input id="email" type="email" name="email" value="{{ old("email") }}" required autofocus autocomplete="username" placeholder="Email"/>

                    <!-- Error messages here e.g. "no email found" -->
                    @error("email")
                        <p>{{ $message }}</p>
                    @enderror
                </div>

                <div class="button-group">
                    <button type="submit" class="btn-send">{{ __("Send") }}</button>
                    <a href="{{ route('login') }}" class="btn-back">{{ __('Go Back') }}</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
