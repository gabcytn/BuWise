<x-guest-layout>
    <div class="otp-container">
        <div class="otp-box">
            <h2>Enter OTP</h2>
            <p>We’ve sent a verification code to <strong></strong></p>
            <form method="POST" action="#">
                @csrf

                <div class="otp-inputs">
                    <input type="text" name="otp[0]" maxlength="1" class="otp-input" required>
                    <input type="text" name="otp[1]" maxlength="1" class="otp-input" required>
                    <input type="text" name="otp[2]" maxlength="1" class="otp-input" required>
                    <input type="text" name="otp[3]" maxlength="1" class="otp-input" required>
                </div>

                <p class="resend-text">Didn't get a code? <a href="#" class="resend-link">Click to resend in 30s</a></p>

                <div class="button-group">
                    <button type="submit" class="btn-verify">{{ __('Verify') }}</button>
                    <a href="#" class="btn-cancel">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>

<style>
    .otp-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #1e2a3a;
    }

    .otp-box {
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

    .otp-inputs {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .otp-input {
        width: 40px;
        height: 40px;
        font-size: 20px;
        text-align: center;
        border-radius: 4px;
        border: none;
        background-color: #fff;
        margin: 0 5px;
    }

    .button-group {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .btn-verify {
        background-color: #4CAF50;
        color: white;
        padding: 10px;
        border-radius: 4px;
        text-align: center;
        border: none;
    }

    .btn-cancel {
        background-color: #7d7d7d;
        color: white;
        padding: 10px;
        border-radius: 4px;
        text-align: center;
        display: block;
        text-decoration: none;
        border: none;
    }

    .resend-text {
        font-size: 12px;
        color: white;
        margin-top: 10px;
    }

    .resend-link {
        color: #4CAF50;
        text-decoration: none;
    }
</style>
