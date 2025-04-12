<x-root-layout>
    @vite(['resources/css/auth/enable-mfa.css'])
    <div class="container">
        @if (!auth()->user()->two_factor_confirmed_at && !session('status'))
            <h3 id="title">Enable Two Factor Authentication Below</h3>
            <form action="/user/two-factor-authentication" method="POST">
                @csrf
                <button id="enable-mfa-button" type="submit">Enable 2FA</button>
            </form>
        @endif

        @if (session('status') === 'two-factor-authentication-enabled')
            <h4 id="subtitle">Scan the QR Code below with your authenticator app and enter the code that appears</h4>
            {!! auth()->user()->twoFactorQrCodeSvg() !!} <br />
            <form action="/user/confirmed-two-factor-authentication" method="POST">
                @csrf
                <input name="code" placeholder="123456" />
                <button id="submit-code-button" type="submit">Submit Code</button>
            </form>
        @endif


        <!-- RECOVERY CODES -->
        @if (session('status') == 'two-factor-authentication-confirmed')
            <p id="confirmed-mfa">Two factor authentication confirmed and enabled successfully.</p>
            <h4 id="recovery-code-reminder">In case of lost authenticator, login through these recovery codes</h4>
            <ul>
                @foreach ((array) auth()->user()->recoveryCodes() as $code)
                    <li>{{ $code }}</li>
                @endforeach
            </ul>
            <a href="{{ route('dashboard') }}">Go to Dashboard</a>
        @endif


        <!-- LOGOUT -->
        <form action="/logout" method="POST">
            @csrf
            <button id="logout-button" type="submit">Log out</button>
        </form>
    </div>
</x-root-layout>
