<x-root-layout>
    @vite(['resources/css/auth/reset-password.css'])

    <img src="{{ asset('images/Buwiselogo.png') }}" alt="BuWise Logo" class="logo" />

    <h2 class="title">Reset Password</h2>
    <p class="description">Enter your email and new password below.</p>

    <form method="POST" action="/reset-password" id="reset-password-form">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" placeholder="Email" required autofocus />
        @error('email')
            <p id="error">{{ $message }}</p>
        @enderror

        <input id="password" name="password" type="password" placeholder="New Password" required />
        @error('password')
            <p id="error">{{ $message }}</p>
        @enderror

        <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm Password" required />
        @error('password_confirmation')
            <p id="error">{{ $message }}</p>
        @enderror

        <button type="submit" class="primary-button">Reset Password</button>
        <button type="button" class="logout-button">Go Back</button>
    </form>

    <script>
        document.querySelector(".logout-button").addEventListener("click", () => {
            window.location.href = window.history.back();
        });
    </script>
</x-root-layout>