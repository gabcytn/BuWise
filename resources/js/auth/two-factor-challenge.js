const inputs = document.querySelectorAll(".otp-input");
const form = document.querySelector("form");
const code = document.querySelector("input[name='code'");

inputs.forEach((input, index) => {
    input.addEventListener("input", (event) => {
        const value = event.target.value;

        // Allow only numeric values
        if (!/^\d*$/.test(value)) {
            event.target.value = "";
            return;
        }

        // Move to the next input box if a digit is entered
        if (value && index < inputs.length - 1) {
            inputs[index + 1].focus();
        }
    });

    input.addEventListener("keydown", (event) => {
        if (event.key === "Backspace" && index > 0 && !event.target.value) {
            // Move to the previous input box
            inputs[index - 1].focus();
        }
    });
});

form.addEventListener("submit", (e) => {
    e.preventDefault();

    // Get value of the individual inputs
    const value = Array.from(inputs)
        .map((input) => input.value)
        .join("");

    code.value = value;
    form.submit();
});

// Recovery Code

const openRecoveryCode = document.querySelector(".bottom-text a");
const recoveryCodeInput = document.querySelector(".bottom-text form");

openRecoveryCode.addEventListener("click", () => {
    recoveryCodeInput.classList.toggle("d-none");
});
const resendLink = document.getElementById("resend-link");

if (resendLink) {
    let countdown = null;

    const startResendTimer = () => {
        let seconds = 30;
        resendLink.textContent = `Resend available in ${seconds}s`;
        resendLink.style.pointerEvents = "none";
        resendLink.style.opacity = "0.6";

        countdown = setInterval(() => {
            seconds--;
            resendLink.textContent = `Resend available in ${seconds}s`;

            if (seconds <= 0) {
                clearInterval(countdown);
                resendLink.textContent = "Click to resend";
                resendLink.style.pointerEvents = "auto";
                resendLink.style.opacity = "1";
            }
        }, 1000);
    };

    resendLink.addEventListener("click", (e) => {
        e.preventDefault();

        alert("A new OTP has been sent to your email.");

        startResendTimer();
    });

    startResendTimer();
}
