<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BuWise</title>
    @vite('resources/css/auth/login.css')
    @vite('resources/js/login.js')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="left-section">
            <img src="{{ asset('images/imgbg.jpg') }}" alt="BuWise" class="login-image">
        </div>

        <div class="right-section">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <h2>Welcome Back!</h2>
                <p id="sub-title">Simplifying and Automating Your Workflow</p>

                {{-- email field --}}
                <div class="input-wrapper">
                    <label for="email">{{ "Email" }}</label>
                    <div class="input-box">
                        <i class="fas fa-envelope"></i>
                        <input id="email" type="email" name="email" value="{{ old("email") }}" required/>
                    </div>
                    @error("email")
                        <p>{{ $errors->message }}</p>
                    @enderror
                </div>

                {{-- password field --}}
                <div class="input-wrapper">
                    <label for="password">{{ "Password" }}</label>
                    <div class="input-box">
                        <i class="fas fa-lock lock-icon"></i>
                        <input id="password" type="password" name="password" required />
                        <i class="fas fa-eye" id="toggle-password"></i>
                    </div>
                    @error("password")
                        <p>{{ $errors->message }}</p>
                    @enderror
                </div>

{{--                <div class="toggle mt-4">--}}
{{--                    <span class="toggle-text"></span>--}}
{{--                </div>--}}

                <div class="forgot-password-container">
                    <a class="#" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                </div>

                <button type="submit">{{ __("Login") }}</button>
            </form>
        </div>
    </div>
</body>
</html>
