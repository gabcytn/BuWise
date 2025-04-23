<x-root-layout>
    @vite(['resources/css/auth/login.css', 'resources/js/login.js'])
    <div class="left-section">
        <img src="{{ asset('images/imgbg.jpg') }}" alt="BuWise" class="login-image">
    </div>

    <div class="right-section">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <h2 id="title">Welcome Back!</h2>
            <p id="subtitle">Simplifying and Automating Your Workflow</p>

            {{-- email field --}}
            <div class="input-wrapper">
                <label for="email">{{ 'Email' }}</label>
                <div class="input-box">
                    <i class="fas fa-envelope"></i>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required />
                </div>
                @error('email')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            {{-- password field --}}
            <div class="input-wrapper">
                <label for="password">{{ 'Password' }}</label>
                <div class="input-box">
                    <i class="fas fa-lock lock-icon"></i>
                    <input id="password" type="password" name="password" required />
                    <i class="fas fa-eye" id="toggle-password"></i>
                </div>
                @error('password')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="remember-container">
                <input name="remember" type="checkbox" id="remember-me" />
                <label for="remember-me">Remember me</label>
            </div>

            <div class="forgot-password-container">
                <a class="#" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            </div>

            <button type="submit">{{ __('Login') }}</button>
        </form>
    </div>
</x-root-layout>
