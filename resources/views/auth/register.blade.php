<x-root-layout>
    @vite(['resources/css/auth/register.css', 'resources/js/auth/register.js'])
    <div class="left-section">
        <img src="{{ asset('images/imgbg.jpg') }}" alt="BuWise" class="register-image">
    </div>
    <div class="right-section">
        <form method="POST" action="{{ route('register') }}" id="register-form">
            @csrf
            <h2 id="title">Welcome</h2>
            <p id="subtitle">Simplifying and Automating Your Workflow</p>
            <div class="input-group">
                <label for="name">Name</label>
                <div class="input-wrapper">
                    <span class="icon"><i class="fas fa-user"></i></span>
                    <input id="name" type="text" name="name" required autocomplete="name">
                </div>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <span class="icon"><i class="fas fa-envelope"></i></span>
                    <input id="email" type="email" name="email" required autocomplete="username">
                </div>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <span class="icon"><i class="fas fa-lock"></i></span>
                    <input id="password" type="password" name="password" required>
                </div>
                <p id="password-feedback" class="feedback"></p>
            </div>
            <div class="input-group">
                <label for="password_confirmation">Confirm Password</label>
                <div class="input-wrapper">
                    <span class="icon"><i class="fas fa-lock"></i></span>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>
                <p id="confirm-feedback" class="weak feedback"></p>
            </div>
            <button type="submit">{{ 'Sign Up' }}</button>
            @if ($errors->any())
                <p style="color: red; font-size: 0.8rem; margin: 0.25rem 0;">{{ $errors->first() }}</p>
            @endif
        </form>
    </div>
    <script src="bower_components/zxcvbn/dist/zxcvbn.js"></script>
</x-root-layout>
