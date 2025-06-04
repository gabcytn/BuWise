<x-root-layout>
    @vite(['resources/css/auth/enable-mfa.css'])

    <img src="{{ asset('images/Buwiselogo.png') }}" alt="BuWise Logo" class="logo" />

    @if (!auth()->user()->two_factor_confirmed_at && !session('status'))
        <h1 class="title">Enable Two Factor Authentication</h1>
        <form action="/user/two-factor-authentication" method="POST">
            @csrf
            <button type="submit" class="primary-button">Enable 2FA</button>
        </form>
    @endif

    @if (session('status') === 'two-factor-authentication-enabled')
        <h1 class="title">Scan the QR Code</h1>
        <p class="description">Scan the QR code below using your authenticator app and enter the code it generates.</p>
        <div class="qr-code">{!! auth()->user()->twoFactorQrCodeSvg() !!}</div>
        <form action="/user/confirmed-two-factor-authentication" method="POST">
            @csrf
            <input name="code" placeholder="123456" />
            <button type="submit" class="primary-button">Submit Code</button>
        </form>
    @endif

    @if (session('status') == 'two-factor-authentication-confirmed')
        <h1 class="title">2FA Enabled!</h1>
        <p class="description">Save these recovery codes in a safe place in case you lose access to your authenticator app.</p>
        <ul class="recovery-codes">
            @foreach ((array) auth()->user()->recoveryCodes() as $code)
                <li>{{ $code }}</li>
            @endforeach
        </ul>
        <a href="{{ route('dashboard') }}" class="link">Go to Dashboard</a>
    @endif

    <form action="/logout" method="POST">
        @csrf
        <button type="submit" class="logout-button">Log out</button>
    </form>
</x-root-layout>
