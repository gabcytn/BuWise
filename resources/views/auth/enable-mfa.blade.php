<!DOCTYPE html>
<html>
<head>
    <!-- Hanken Grotesk -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
@vite(['resources/css/auth/enable-mfa.css'])
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
            <h4>Scan the QR Code below with your authenticator app.</h4>
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
