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
                <p>Simplifying and Automating Your Workflow</p>

                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i> 
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

<div class="input-wrapper mt-4">
    <i class="fas fa-lock lock-icon"></i> 
    <x-input-label for="password" :value="__('Password')" />
    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
    <x-input-error :messages="$errors->get('password')" class="mt-2" />
    
</div>
<div class="toggle mt-4">
    <i class="fas fa-eye" id="toggle-password" class="eye-icon"></i>
    <span class="toggle-text"></span>
</div>



                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" name="remember">
                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <button type="submit">{{ __("Login") }}</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
