<x-guest-layout>
    <div class="reset-password-container">
        <div class="reset-password-box">
            <h2>Reset Password</h2>
            <p>Enter Email</p>
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="input-group">
                    <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Email" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="button-group">
                    <x-primary-button class="btn-send">
                        {{ __('Send') }}
                    </x-primary-button>
                    <a href="{{ route('login') }}" class="btn-back">{{ __('Go Back') }}</a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>

<style>
    .reset-password-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #1e2a3a;
    }

    .reset-password-box {
        background: #2c3e50;
        padding: 40px;
        border-radius: 8px;
        text-align: center;
        width: 350px;
    }

    h2 {
        color: white;
        font-size: 24px;
        margin-bottom: 20px;
    }

    p {
        color: white;
        font-size: 14px;
        margin-bottom: 10px;
    }

    .input-group {
        margin-bottom: 20px;
    }

    .block {
        width: 100%;
        padding: 10px;
        border-radius: 4px;
        border: none;
    }

    .button-group {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .btn-send {
        background-color: #4CAF50;
        color: white;
        padding: 10px;
        border-radius: 4px;
        text-align: center;
        display: block;
        text-decoration: none;
    }

    .btn-back {
        background-color: #7d7d7d;
        color: white;
        padding: 10px;
        border-radius: 4px;
        text-align: center;
        display: block;
        text-decoration: none;
    }
</style>
