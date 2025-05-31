<x-root-layout>
    @vite(['resources/css/auth/t&c.css', 'resources/js/auth/register.js'])

    <div class="register-page">
        <!-- Left Illustration Section -->
        <div class="left-section">
            <img src="{{ asset('images/Buwiselogo.png') }}" alt="Buwise Logo" class="logo">
            <img src="{{ asset('images/main.png') }}" alt="Register Illustration" class="illustration">
        </div>

        <!-- Right Terms & Conditions Section -->
        <div class="right-section">
            <div class="terms-container">
                <h2 class="terms-title">Terms & Conditions</h2>
                <div class="terms-content">
                    <p>
                        By registering, you agree to abide by our platform's terms and conditions.
                        This includes responsible usage of services, safeguarding of confidential
                        information, and compliance with all applicable laws and platform policies.
                    </p>
                    <p>
                        Please review the full agreement to understand your rights and responsibilities
                        before proceeding with your account registration.
                    </p>
                </div>

                <div class="terms-buttons">
                    <button class="btn-secondary">Decline</button>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <input type="hidden" name="agreed" value="yes">
                        <button type="submit" class="btn-primary">Accept</button>
                    </form>
                </div>

                <p class="bottom-text">Already have an account? <a href="{{ route('login') }}">Log In</a></p>
            </div>
        </div>
    </div>
</x-root-layout>
