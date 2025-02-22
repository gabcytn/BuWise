<x-guest-layout>
    <div class="register-container">
        <div class="left-section">
            <img src="{{ asset('path/to/images/imgbg.jpg') }}" alt="BuWise" class="register-image">
        </div>

        <div class="right-section">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <h2>Welcome!</h2>
                <p>Simplifying and Automating Your Workflow</p>

                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
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
</x-guest-layout>

<style>
    .register-container {
        display: flex;
        height: 100%;
        width: 100%;
    }

    .left-section {
        width: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f4f5f7;
    }

    .right-section {
        width: 50%;
        padding: 30px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    .register-image {
        max-width: 100%;
        height: auto;
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
