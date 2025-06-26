<x-root-layout>
    @vite(['resources/css/auth/login.css', 'resources/js/auth/login.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <!-- Navigation -->
    <nav class="navbar">
        <div class="container nav-wrapper">
            <img src="/images/buwiselogo.png" alt="BuWise Logo" class="logo">
            <div class="nav-text">
                <span>Already have a BuWise account?</span>
                <a href="/register" class="signin-link">SIGN UP</a>
            </div>
        </div>
    </nav>

    <div class="register-page">
        <!-- Left Section -->
        <div class="left-section">
            <img src="{{ asset('images/hero-login.png') }}" alt="Register Illustration" class="illustration hero-img">
        </div>

        <!-- Right Section -->
        <div class="right-section">
            <form method="POST" action="{{ route('login') }}" class="form-card" id="register-form">
                @csrf

                <h1 class="title">Welcome back to <span class="blue">BuWise!</span></h1>
                <p class="subtitle">Please login to your account to access your</p>
                <p class="subtitle">organization.</p>

                <!-- Email -->
                <div class="input-group">
                    <label for="email">Email Address</label>
                    <div class="input-box">
                        <i class="fas fa-envelope icon"></i>
                        <input id="email" type="email" name="email" placeholder="name@domain.com" required>
                    </div>
                </div>

                <!-- Password -->
                <div class="input-group">
    <label for="password">Password</label>
    <div class="input-box">
        <i class="fas fa-lock icon"></i>
        <input id="password" type="password" name="password" placeholder="Enter your password" required>
        <i class="fas fa-eye toggle-password" data-target="password"></i>
    </div>
</div>


                <!-- Options -->
                <div class="form-options">
                    <label class="remember">
                        <input type="checkbox" name="remember">
                        Remember Me
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot">Forgot Password?</a>
                </div>

                <button type="submit" class="btn-primary">Log In</button>

                @if ($errors->any())
                    <p class="error-msg">{{ $errors->first() }}</p>
                @endif
            </form>
        </div>
    </div>
</x-root-layout>
