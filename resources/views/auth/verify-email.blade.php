<x-root-layout>
    @vite('resources/css/auth/verify-email.css')

    <nav class="navbar">
        <div class="nav-wrapper">
            <img src="/images/buwiselogo.png" alt="BuWise Logo" class="logo">
        </div>
    </nav>

    <div class="verify-wrapper">
        <img src="/images/hero-verification.png" alt="Illustration" class="verify-illustration" />

        <h1 class="verify-title">You’ve created your account!</h1>

        <p class="verify-message">
            Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you. Check your spam folder if it's not visible.
        </p>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="verify-btn green">{{ __('Resend Verification Email') }}</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="verify-btn gray">{{ __('Log Out') }}</button>
        </form>

        @if (session('status') == 'verification-link-sent')
            <p class="session-status">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </p>
        @endif

        <p class="footer-text">© 2025, Buwise Inc. All Rights Reserved.</p>
    </div>
</x-root-layout>
