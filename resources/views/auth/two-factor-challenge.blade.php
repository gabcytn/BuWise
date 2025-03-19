<!DOCTYPE html>
<html>
<head>
    <title>{{ config("app.name") }}</title>
</head>
<body>
    @vite(["resources/css/auth/two-factor-challenge.css", "resources/js/auth/two-factor-challenge.js"])
    <div class="otp-container">
        <div class="otp-box">
            <h2>Enter OTP</h2>
            <p>Open your authenticator app and input your 6-digit pin</p>
            <form method="POST" action="/two-factor-challenge">
                @csrf

                <div class="otp-inputs">
                    <input type="text" maxlength="1" class="otp-input" required>
                    <input type="text" maxlength="1" class="otp-input" required>
                    <input type="text" maxlength="1" class="otp-input" required>
                    <input type="text" maxlength="1" class="otp-input" required>
                    <input type="text" maxlength="1" class="otp-input" required>
                    <input type="text" maxlength="1" class="otp-input" required>
                </div>

                <input type="hidden" type="text" name="code"/>

                <div class="button-group">
                    <button type="submit" class="btn-verify">{{ __('Verify') }}</button>
                </div>
            </form>

            <form action="/logout" method="POST" id="form-cancel">
                @csrf
                <button type="submit" class="btn-cancel">Cancel</button>
            </form>
        </div>
    </div>
</body>
</html>
