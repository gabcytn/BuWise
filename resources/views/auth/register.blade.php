<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BuWise</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    @vite('resources/css/auth/register.css')
    @vite('resources/js/register.js')
</head>
<body>

    <div class="register-container">
        <div class="left-section">
            <img src="{{ asset('images/imgbg.jpg') }}" alt="BuWise" class="register-image">
        </div>

        <div class="right-section">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <h2>Welcome!</h2>
                <p>Simplifying and Automating Your Workflow</p>

                <div class="input-group">
                    <label for="name">Name</label>
                    <div class="input-wrapper">
                        <span class="icon"><i class="fas fa-user"></i></span>
                        <input id="name" type="text" name="name" required autocomplete="name">
                    </div>
                </div>
                
                <div class="input-group mt-4">
                    <label for="email">Email</label>
                    <div class="input-wrapper">
                        <span class="icon"><i class="fas fa-envelope"></i></span>
                        <input id="email" type="email" name="email" required autocomplete="username">
                    </div>
                </div>
                
                <div class="input-group mt-4">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <span class="icon"><i class="fas fa-lock"></i></span>
                        <input id="password" type="password" name="password" required autocomplete="new-password">
                    </div>
                </div>
                
                <div class="input-group mt-4">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="input-wrapper">
                        <span class="icon"><i class="fas fa-lock"></i></span>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>
                

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <x-primary-button class="ms-3">
                        {{ __('Sign Up') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
