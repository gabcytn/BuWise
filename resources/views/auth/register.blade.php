<x-root-layout>
    @vite('resources/css/auth/register.css')
    <div class="left-section">
        <img src="{{ asset('images/imgbg.jpg') }}" alt="BuWise" class="register-image">
    </div>
    <div class="right-section">
        <form method="POST" action="{{ route('register') }}">
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
            </div>
            <div class="input-group">
                <label for="password_confirmation">Confirm Password</label>
                <div class="input-wrapper">
                    <span class="icon"><i class="fas fa-lock"></i></span>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>
            </div>
            <button type="submit">{{ 'Sign Up' }}</button>
        </form>
    </div>
</x-root-layout>
