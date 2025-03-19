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
