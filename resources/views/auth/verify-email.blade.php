<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>


    <!-- Inter and Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100dvh;
            background-color: #133E5A;
        }

        .container {
            max-width: 30rem;
            text-align: center;
            background: #EEE;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        #greeting {
            font-family: 'Inter', sans-serif;
        }

        p {
            font-size: 1rem;
            color: #333;
            margin-bottom: 20px;
        }

        .message {
            color: green;
            font-size: 0.95rem;
            margin-bottom: rem;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            width: 100%;
            margin-top: 10px;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        form {
            margin-top: 10px;
        }

        .logout-button {
            background-color: #dc3545;
        }

        .logout-button:hover {
            background-color: #a71d2a;
        }
    </style>
</head>
<body>

    <div class="container">
        <p id="greeting">{{ __('Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you.') }}</p>

        @if (session('status') == 'verification-link-sent')
            <p class="message">{{ __('A new verification link has been sent to the email address you provided during registration.') }}</p>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit">{{ __('Resend Verification Email') }}</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-button">{{ __('Log Out') }}</button>
        </form>
    </div>

</body>
</html>
