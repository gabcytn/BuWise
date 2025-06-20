<x-root-layout>
    @vite(['resources/css/auth/two-factor-challenge.css', 'resources/js/auth/two-factor-challenge.js'])

    <div class="section-wrapper">
        <div class="left-section">
            <img src="/images/Buwiselogo.png" alt="Logo" class="logo" />
            <img src="/images/main.png" alt="OTP Illustration" class="illustration" />
        </div>

        <div class="right-section">
            <div class="form-card">
                <h2 class="title">Enter <span>OTP</span></h2>
                <p class="subtitle">Open your authenticator app and input your 6-digit pin</p>

                <form method="POST" action="/two-factor-challenge">
                    @csrf
                    <div class="otp-inputs">
                        <input type="text" maxlength="1" class="otp-input" required />
                        <input type="text" maxlength="1" class="otp-input" required />
                        <input type="text" maxlength="1" class="otp-input" required />
                        <input type="text" maxlength="1" class="otp-input" required />
                        <input type="text" maxlength="1" class="otp-input" required />
                        <input type="text" maxlength="1" class="otp-input" required />
                    </div>

                    @if ($errors->any())
                        <p class="error-msg">{{ $errors->first() }}</p>
                    @endif

                    <input type="hidden" name="code" />

                    <button type="submit" class="btn-primary">{{ __('Verify') }}</button>
                </form>

                <form method="POST" action="/logout" id="form-cancel">
                    @csrf
                    <button type="submit" class="btn-primary"
                        style="margin-top: 1rem; background-color: transparent; border: 1px solid #fff; color: #fff;">Cancel</button>
                </form>

                <div class="bottom-text">
    <label for="toggle-recovery">Login with a recovery code</label>
    <input type="checkbox" id="toggle-recovery" class="toggle-recovery">
    <form method="POST" action="/two-factor-challenge" class="recovery-form">
        @csrf
        <input name="recovery_code" placeholder="Enter recovery code" />
    </form>
</div>

            </div>
        </div>
    </div>
</x-root-layout>
