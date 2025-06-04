<x-root-layout>
    @vite('resources/css/auth/forgot-password.css')

    <img src="{{ asset('images/Buwiselogo.png') }}" alt="BuWise Logo" class="logo" />

    <h2 class="title">Forgot Password</h2>
    <p class="description">Enter your email to reset your password</p>
    <form action="/forgot-password" method="POST">
        @csrf
        <input type="email" name="email" placeholder="accountant@domain.com" required>
        <button type="submit" class="primary-button">Send</button>
        <button type="button" class="logout-button">Go Back</button>
    </form>
    @if (session('status'))
        <p id="session-status">{{ session('status') }}</p>
    @endif
    @if ($errors->any())
        <p style="color: red; font-size: 14px;">{{ $errors->first() }}</p>
    @endif

    <script>
        document.querySelector("button.logout-button").addEventListener("click", () => {
            window.location.href = window.history.back();
        });
    </script>
</x-root-layout>
