<x-root-layout>
    @vite('resources/css/auth/verify-email.css')

    <div class="container">
        <img src="{{ asset('images/Buwiselogo.png') }}" alt="BuWise Logo" class="logo" />

        <h1 class="title">Successful!</h1>

        <p id="greeting">
            {{ __('Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you.') }}
        </p>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" id="verify-button">{{ __('Resend Verification Email') }}</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" id="logout-button">{{ __('Log Out') }}</button>
        </form>

        @if (session('status') == 'verification-link-sent')
            <p id="session-status">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </p>
        @endif
    </div>
</x-root-layout>
