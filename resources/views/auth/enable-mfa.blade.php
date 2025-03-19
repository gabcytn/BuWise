<!DOCTYPE html>
<html>
<head>
    <title>{{ config("app.name") }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        .container {
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Enable Two Factor Authentication Below</h3>
        <form action="/user/two-factor-authentication" method="POST">
            @csrf
            @if (auth()->user()->two_factor_confirmed_at)
                @method("DELETE")
                <button type="submit">Disable 2FA</button>
            @else
                <button type="submit">Enable 2FA</button>
            @endif
        </form>

        @if (session('status') === 'two-factor-authentication-enabled')
            <h4>Please finish configuring two factor authentication below.</h4>
            {!! auth()->user()->twoFactorQrCodeSvg() !!} <br />
            <form action="/user/confirmed-two-factor-authentication" method="POST">
                @csrf
                <label for="code">Code</label>
                <input name="code" id="code" />
                <button type="submit">Submit Code</button>
            </form>
        @endif


        <!-- RECOVERY CODES -->
        @if (session('status') == 'two-factor-authentication-confirmed')
            <p>Two factor authentication confirmed and enabled successfully.</p>
            <h4>In case of lost authenticator, login through these recovery codes</h4>
            <ul>
                @foreach ((array) auth()->user()->recoveryCodes() as $code)
                    <li>{{ $code }}</li>
                @endforeach
            </ul>
            <a href="{{ route("dashboard") }}">Go to Dashboard</a>
        @endif


        <!-- LOGOUT -->
        <form action="/logout" method="POST">
            @csrf
            <button type="submit">Log out</button>
        </form>
    </div>
</body>
</html>
