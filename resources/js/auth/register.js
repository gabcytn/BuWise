const password = document.querySelector("#password");
const confirmPassword = document.querySelector("#password_confirmation");
const passwordFeedback = document.querySelector("#password-feedback");
const confirmFeedback = document.querySelector("#confirm-feedback");
const form = document.querySelector("#register-form");
const submitButton = document.querySelector("button[type='submit']");

const strengthChart = new Map();
strengthChart.set(0, "Very weak");
strengthChart.set(1, "Weak");
strengthChart.set(2, "Medium");
strengthChart.set(3, "Strong");
strengthChart.set(4, "Very Strong");

password.addEventListener("input", (e) => {
    const v = zxcvbn(password.value);
    if (password.value === "")
        passwordFeedback.textContent = ""
    else {
        passwordFeedback.textContent = strengthChart.get(v.score);
        updateFeedback(v.score)
    }

})

confirmPassword.addEventListener("input", () => {
    if (confirmPassword.value !== password.value && confirmPassword.value !== "") {
        confirmFeedback.textContent = "Passwords do not match";
    } else {
        confirmFeedback.textContent = "";
    }
})

function updateFeedback(score) {
    switch (score) {
        case 0:
        case 1:
            clearFeedbackClassName()
            passwordFeedback.classList.add("weak");
            break;
        case 2:
            clearFeedbackClassName()
            passwordFeedback.classList.add("medium")
            break;
        case 3:
        case 4:
            clearFeedbackClassName()
            passwordFeedback.classList.add("strong")
            break;
        default:
            clearFeedbackClassName()
    }
}

function clearFeedbackClassName() {
    passwordFeedback.className = "feedback";
}

form.addEventListener("submit", (e) => {
    if (zxcvbn(password.value).score <= 2) {
        e.preventDefault();
    }
})
