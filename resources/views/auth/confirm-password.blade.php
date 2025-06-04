<x-root-layout>
    @vite('resources/css/auth/confirm-password.css')

    <img src="{{ asset('images/Buwiselogo.png') }}" alt="BuWise Logo" class="logo" />

    <h2 class="title">Confirm Password</h2>
    <p class="description">This is a secure area of the application. Please confirm your password before continuing.</p>
    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <input id="password" type="password" name="password" placeholder="Your password" required autocomplete="current-password" />
        <button type="submit" class="primary-button">Confirm</button>
        <button type="button" class="logout-button">Go Back</button>
    </form>
    @error('password')
        <p>{{ $message }}</p>
    @enderror

    <script>
        document.querySelector("button.logout-button").addEventListener("click", () => {
            window.location.href = window.history.back();
        });
    </script>
</x-root-layout>