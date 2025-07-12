<x-root-layout>
    @vite(['resources/css/auth/register.css', 'resources/js/auth/register.js'])

    <!-- Navigation -->
    <nav class="navbar">
        <div class="container nav-wrapper">
            <img src="/images/buwiselogo.png" alt="BuWise Logo" class="logo">
            <div class="nav-text">
                <span>Already have a BuWise account?</span>
                <a href="/login" class="signin-link">SIGN IN</a>
            </div>
        </div>
    </nav>

    <div class="register-page">
        <!-- Left Illustration Section -->
        <div class="left-section">
            <img src="{{ asset('images/hero-register.png') }}" alt="Register Illustration"
                class="illustration hero-img">
        </div>

        <!-- Right Form Section -->
        <div class="right-section">
            <form method="POST" action="{{ route('register') }}" class="form-card" id="register-form">
                @csrf

                <h1 class="title">Start as an <span class="blue">Accountant</span></h1>
                <p class="subtitle">Create an account to manage your clients, staff, </p>
                <p class="subtitle"> and organization. All in one platform.</p>


                <div class="input-group full-name-group">
                    <label for="name">Full Name</label>
                    <div class="input-box">
                        <i class="fas fa-user icon"></i>
                        <input id="name" type="text" name="name" placeholder="Enter your full name" required
                            autocomplete="name">
                    </div>
                </div>

                <div class="input-group">
                    <label for="email">Email Address</label>
                    <div class="input-box">
                        <i class="fas fa-envelope icon"></i>
                        <input id="email" type="email" name="email" placeholder="name@domain.com" required
                            autocomplete="username">
                    </div>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-box">
                        <i class="fas fa-lock icon"></i>
                        <input id="password" type="password" name="password" placeholder="Enter your password"
                            required>
                        <i class="fas fa-eye toggle-password"></i>
                    </div>

                    <div class="password-criteria">
                        <div class="criteria-item" id="strength-check">
                            <i class="fas fa-times icon-x"></i>
                            <span>Password Strength: <span id="password-feedback" class="feedback">Weak</span></span>
                        </div>
                        <div class="criteria-item" id="length-check">
                            <i class="fas fa-times icon-x"></i>
                            <span>At least 8 characters</span>
                        </div>
                        <div class="criteria-item" id="combo-check">
                            <i class="fas fa-times icon-x"></i>
                            <span>Any combination of letters, numbers, and symbols</span>
                        </div>
                    </div>
                </div>


                <div class="input-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="input-box">
                        <i class="fas fa-lock icon"></i>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                            placeholder="Enter your password again" required>
                        <i class="fas fa-eye toggle-password"></i>
                    </div>

                    <p id="confirm-feedback" class="feedback"></p>
                </div>


                <button type="submit" class="btn-primary">Create Account</button>

                @if ($errors->any())
                    <p class="error-msg">{{ $errors->first() }}</p>
                @endif
            </form>
        </div>
    </div>

    <script src="bower_components/zxcvbn/dist/zxcvbn.js"></script>
</x-root-layout>
