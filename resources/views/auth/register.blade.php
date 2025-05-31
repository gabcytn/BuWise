<x-root-layout>
    @vite(['resources/css/auth/register.css', 'resources/js/auth/register.js'])

    <div class="register-page">
        <!-- Left Illustration Section -->
        <div class="left-section">
            <img src="{{ asset('images/Buwiselogo.png') }}" alt="Buwise Logo" class="logo">
            <img src="{{ asset('images/main.png') }}" alt="Register Illustration" class="illustration">
        </div>

        <!-- Right Form Section -->
        <div class="right-section">
            <form method="POST" action="{{ route('register') }}" class="form-card">
                @csrf

                <h1 class="title">Start as an<br><span>Accountant</span></h1>
                <p class="subtitle">Manage your staff and clients online</p>

                <div class="input-group">
                    <label>Email</label>
                    <div class="input-box">
                        <i class="fas fa-envelope icon"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <div class="input-box">
                        <i class="fas fa-lock icon"></i>
                        <input type="password" name="password" required>
                        <i class="fas fa-eye toggle-password"></i>
                    </div>
                </div>

                <div class="input-group">
                    <label>Confirm Password</label>
                    <div class="input-box">
                        <i class="fas fa-lock icon"></i>
                        <input type="password" name="password_confirmation" required>
                        <i class="fas fa-eye toggle-password"></i>
                    </div>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="terms" required>
                    <label for="terms">I agree to the <span class="bold">Terms and Conditions</span></label>
                </div>

                <button type="submit" class="btn-primary">Sign Up</button>

                <p class="bottom-text">Already have an account? <a href="{{ route('login') }}">Log In</a></p>

                @if ($errors->any())
                    <p class="error-msg">{{ $errors->first() }}</p>
                @endif
            </form>
        </div>
    </div>
</x-root-layout>
