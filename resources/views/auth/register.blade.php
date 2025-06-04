<x-root-layout>
    @vite(['resources/css/auth/register.css', 'resources/js/auth/register.js'])

    <div class="register-page">
        <!-- Left Illustration Section -->
        <div class="left-section">
            <img src="{{ asset('images/Buwiselogo.png') }}" alt="Buwise Logo" class="logo">
            <img src="{{ asset('images/main.png') }}" alt="Register Illustration" class="illustration">
        </div>

        <!-- Right Form Section -->
        <div class="right-section">
            <form method="POST" action="{{ route('register') }}" class="form-card" id="register-form">
                @csrf

                <h1 class="title">Start as an<br><span>Accountant</span></h1>
                <p class="subtitle">Manage your staff and clients online</p>

                <div class="input-group">
                    <label for="name">Name</label>
                    <div class="input-box">
                        <i class="fas fa-user icon"></i>
                        <input id="name" type="text" name="name" required autocomplete="name">
                    </div>
                </div>

                <div class="input-group">
                    <label for="email">Email</label>
                    <div class="input-box">
                        <i class="fas fa-envelope icon"></i>
                        <input id="email" type="email" name="email" required autocomplete="username">
                    </div>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-box">
                        <i class="fas fa-lock icon"></i>
                        <input id="password" type="password" name="password" required>
                        <i class="fas fa-eye toggle-password"></i>
                    </div>
                    <p id="password-feedback" class="feedback"></p>
                </div>

                <div class="input-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="input-box">
                        <i class="fas fa-lock icon"></i>
                        <input id="password_confirmation" type="password" name="password_confirmation" required>
                        <i class="fas fa-eye toggle-password"></i>
                    </div>
                    <p id="confirm-feedback" class="weak feedback"></p>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="terms" required>
                    <label for="terms">I agree to the <span class="bold tnc-link" style="cursor:pointer;">Terms and Conditions</span></label>
                </div>

                <button type="submit" class="btn-primary">Sign Up</button>

                @if ($errors->any())
                    <p style="color: red; font-size: 0.8rem; margin: 0.25rem 0;">{{ $errors->first() }}</p>
                @endif
            </form>
        </div>
    </div>

    <!-- Optional: Terms & Conditions Modal (same as your 2nd code) -->
    <div id="tncModal" class="modal-overlay" style="display:none;">
        <div class="modal-content">
            <h2>Terms and Conditions</h2>
            <div class="modal-body">
                <p>
                    <!-- Your actual terms and conditions text here -->
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus luctus urna sed urna ultricies ac tempor dui sagittis. In condimentum facilisis porta. Sed nec diam eu diam mattis viverra.
                    Nulla fringilla, orci ac euismod semper, magna diam porttitor mauris, quis sollicitudin sapien justo in libero.
                </p>
            </div>
            <div class="modal-footer">
                <button id="declineBtn" class="btn-decline">Decline</button>
                <button id="acceptBtn" class="btn-accept">Accept</button>
            </div>
        </div>
    </div>

    <script src="bower_components/zxcvbn/dist/zxcvbn.js"></script>
</x-root-layout>
