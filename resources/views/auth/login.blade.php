<x-root-layout>
    @vite(['resources/css/auth/login.css', 'resources/js/login.js'])

    <div class="section-wrapper">
        <!-- Left Section -->
        <div class="left-section">
            <img src="{{ asset('images/Buwiselogo.png') }}" alt="Buwise Logo" class="logo">
            <img src="{{ asset('images/main2.png') }}" alt="Register Illustration" class="illustration">
        </div>

        <!-- Right Section -->
        <div class="right-section">
            <form method="POST" action="{{ route('login') }}" class="form-card">
                @csrf

                <h2 class="title">Welcome Back!</h2>
                <p class="subtitle">Simplifying and Automating Your Workflow</p>

                <!-- Email Field -->
                <div class="input-group">
                    <label for="email">Email</label>
                    <div class="input-box">
                        <i class="fas fa-envelope icon"></i>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required />
                    </div>
                    @error('email')
                        <p class="error-msg">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-box">
                        <i class="fas fa-lock icon"></i>
                        <input id="password" type="password" name="password" required />
                        <i class="fas fa-eye toggle-password" id="toggle-password"></i>
                    </div>
                    @error('password')
                        <p class="error-msg">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember me + Forgot password -->
                <div class="form-footer">
                    <div class="checkbox-group">
                        <input type="checkbox" name="remember" id="remember-me">
                        <label for="remember-me">Remember me</label>
                    </div>
                    <div class="forgot-password">
                        <a href="{{ route('password.request') }}">Forgot your password?</a>
                    </div>
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn-primary">
                    {{ __('Login') }}
                </button>
            </form>
        </div>
    </div>
</x-root-layout>
