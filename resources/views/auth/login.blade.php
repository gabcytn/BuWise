<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BuWise</title>
    <style>
        * {
            margin: 0;
            box-sizing: border-box;
        }
        .login-container {
            display: flex;
            min-height: 100dvh;
        }

        .left-section {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f5f7;
        }

        .right-section {
            width: 100%;
            padding: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .login-image {
            width: 100%;
            height: 100dvh;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        p {
            font-size: 14px;
            margin-bottom: 30px;
            color: gray;
        }
    </style>
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

                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
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
</body>

</html>
