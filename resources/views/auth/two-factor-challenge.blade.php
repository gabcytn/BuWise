<x-root-layout>
    @vite(['resources/css/auth/two-factor-challenge.css', 'resources/js/auth/two-factor-challenge.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <!-- Navigation -->
    <nav class="navbar">
        <div class="container nav-wrapper">
            <img src="/images/buwiselogo.png" alt="BuWise Logo" class="logo">
            <div class="nav-text">
                <span>Want to be an accountant?</span>
                <a href="/register" class="signin-link">SIGN UP</a>
            </div>
        </div>
    </nav>

    <div class="register-page">
        <!-- Left Image Section -->
        <div class="left-section">
            <img src="/images/hero-login.png" alt="Illustration" class="illustration">
        </div>

        <!-- Right Form Section -->
        <div class="right-section">
            <div class="form-card">
                <h2 class="title">Enter <span>One–Time Password (OTP)</span></h2>

                @php
                    $email = old('email') ?? session('email') ?? (auth()->user()->email ?? null);
                @endphp

                @if ($email)
                    <p class="subtitle">We’ve sent a verification code to <a href="#">{{ $email }}</a></p>
                @else
                    <p class="subtitle">We’ve sent a verification code to your email</p>
                @endif

                <!-- OTP Form -->
                <form method="POST" action="/two-factor-challenge">
                    @csrf
                    <div class="otp-inputs">
                        <input type="text" maxlength="1" class="otp-input" required />
                        <input type="text" maxlength="1" class="otp-input" required />
                        <input type="text" maxlength="1" class="otp-input" required />
                        <input type="text" maxlength="1" class="otp-input" required />
                        <input type="text" maxlength="1" class="otp-input" required />
                        <input type="text" maxlength="1" class="otp-input" required />
                    </div>

                    @if ($errors->any())
        <p class="error-msg">{{ $errors->first() }}</p>
    @endif

    <!-- Move this here -->
   <div class="bottom-text resend">
    <p>Didn’t get a code? <a href="#" id="resend-link">Click to resend in 30s</a></p>
</div>


    <input type="hidden" name="code" />

    <button type="submit" class="btn-primary">{{ __('Verify') }}</button>
</form>

                <!-- Cancel Button -->
                <form method="POST" action="/logout" id="form-cancel">
                    @csrf
                    <button type="submit" class="btn-primary"
                        style="margin-top: 1rem; background-color: transparent; border: 1px solid #fff; color: #fff;">Cancel</button>
                </form>

                <!-- Recovery Code Section -->
                <div class="bottom-text">
                    <form method="POST" action="/two-factor-challenge" class="recovery-form" style="display: none;">
                        @csrf
                        <input name="recovery_code" placeholder="Enter recovery code" />
                        <button type="submit" class="btn-primary" style="margin-top: 1rem;">Submit Recovery Code</button>
                    </form>
                </div>

               
                </div>
            </div>
        </div>
    </div>
</x-root-layout>
