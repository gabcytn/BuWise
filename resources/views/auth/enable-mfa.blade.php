<x-root-layout>
    @vite(['resources/css/auth/enable-mfa.css'])

    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-wrapper">
            <img src="/images/buwiselogo.png" alt="BuWise Logo" class="logo">
        </div>
    </nav>

    <!-- Main Content -->
    <div class="verify-wrapper">
        {{-- ✅ Hide image if QR or recovery codes are shown --}}
        @if (!in_array(session('status'), ['two-factor-authentication-enabled', 'two-factor-authentication-confirmed']))
            <img src="/images/hero-verification.png" alt="Illustration" class="verify-illustration" />
        @endif

        @if (!auth()->user()->two_factor_confirmed_at && !session('status'))
            <h1 class="verify-title">Enable <span style="color: #1B80C3;">Two Factor Authentication</span></h1>
            <p class="verify-message">
                Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you. Check your spam folder if it's not visible.
            </p>
            <form action="/user/two-factor-authentication" method="POST">
                @csrf
                <button type="submit" class="verify-btn green">Enable 2FA</button>
            </form>
        @endif

        @if (session('status') === 'two-factor-authentication-enabled')
            <h1 class="verify-title" style="color: #1B80C3;">Scan the QR Code</h1>
            <p class="verify-message">
                Scan the QR code below using your authenticator app and enter the code it generates.
            </p>
            <div class="qr-code">{!! auth()->user()->twoFactorQrCodeSvg() !!}</div>
            <form action="/user/confirmed-two-factor-authentication" method="POST">
                @csrf
                <input name="code" placeholder="123456" class="verify-input" />
                <button type="submit" class="verify-btn green">Submit Code</button>
            </form>
        @endif

        @if (session('status') == 'two-factor-authentication-confirmed')
            <h1 class="verify-title">Enable <span style="color: #1B80C3;">Two-Factor Authentication</span></h1>
            <p class="verify-message">
                Save these recovery codes in a safe place in case you lose access to your authenticator app.
            </p>
            <ul class="recovery-codes">
                @foreach ((array) auth()->user()->recoveryCodes() as $code)
                    <li>{{ $code }}</li>
                @endforeach
            </ul>
            <a href="{{ route('dashboard') }}" class="verify-btn green" style="white-space: nowrap;">Go to Dashboard</a>
        @endif

        <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="verify-btn gray">Log out</button>
        </form>

        <p class="footer-text">© 2025 Winxify. All rights reserved.</p>
    </div>
</x-root-layout>
