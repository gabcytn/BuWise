const password = document.querySelector("#password");
const confirmPassword = document.querySelector("#password_confirmation");
const passwordFeedback = document.querySelector("#password-feedback");
const confirmFeedback = document.querySelector("#confirm-feedback");
const form = document.querySelector("#register-form");
const submitButton = document.querySelector("button[type='submit']");
const passwordCriteriaContents = document.querySelector(".password-criteria");

const strengthChart = new Map();
strengthChart.set(0, "Very weak");
strengthChart.set(1, "Weak");
strengthChart.set(2, "Medium");
strengthChart.set(3, "Strong");
strengthChart.set(4, "Very Strong");

const strengthCheck = document.getElementById("strength-check");
const lengthCheck = document.getElementById("length-check");
const comboCheck = document.getElementById("combo-check");
password.addEventListener("input", () => {
    const val = password.value;
    if (!val) {
        passwordCriteriaContents.style.display = "none";
        return;
    }

    const v = zxcvbn(password.value);
    passwordCriteriaContents.style.display = "flex";
    passwordFeedback.textContent = strengthChart.get(v.score);
    updateFeedbackColor(v.score);

    updateCriteriaIcon(lengthCheck, val.length >= 8);
    updateCriteriaIcon(comboCheck, passwordCombinationCheck(val));
    updateCriteriaIcon(strengthCheck, v.score >= 3); // Strong = score 3 or 4

    validateConfirmPassword();
});

function validateConfirmPassword() {
    if (
        confirmPassword.value !== password.value &&
        confirmPassword.value !== ""
    ) {
        confirmFeedback.textContent = "Passwords do not match";
    } else {
        confirmFeedback.textContent = "";
    }
}

confirmPassword.addEventListener("input", validateConfirmPassword);

function updateFeedbackColor(score) {
    switch (score) {
        case 0:
        case 1:
            clearFeedbackClassName();
            passwordFeedback.classList.add("weak");
            break;
        case 2:
            clearFeedbackClassName();
            passwordFeedback.classList.add("medium");
            break;
        case 3:
        case 4:
            clearFeedbackClassName();
            passwordFeedback.classList.add("strong");
            break;
        default:
            clearFeedbackClassName();
    }
}

function clearFeedbackClassName() {
    passwordFeedback.className = "feedback";
}

function updateCriteriaIcon(element, passed) {
    const icon = element.querySelector("i");
    icon.className = passed ? "fas fa-check icon-check" : "fas fa-times icon-x";
}

// email validation
const emailInput = document.querySelector("input#email");
const emailInputFeedback = document.querySelector("#email-input-feedback");
emailInput.addEventListener("input", () => {
    const val = emailInput.value;

    if (validateEmail(val)) {
        emailInputFeedback.style.display = "none";
        return;
    }
    if (val) {
        emailInputFeedback.style.display = "inline";
    } else {
        emailInputFeedback.style.display = "none";
    }
});
const validateEmail = (email) => {
    return email.match(
        /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
    );
};

// Password toggle functionality
document.querySelectorAll(".toggle-password").forEach((icon) => {
    icon.addEventListener("click", () => {
        const input = icon.previousElementSibling;
        const isPassword = input.getAttribute("type") === "password";
        input.setAttribute("type", isPassword ? "text" : "password");
        icon.classList.toggle("fa-eye");
        icon.classList.toggle("fa-eye-slash");
    });
});

function passwordCombinationCheck(val) {
    return (
        /[A-Za-z]/.test(val) && (/[0-9]/.test(val) || /[^A-Za-z0-9]/.test(val))
    );
}

form.addEventListener("submit", (e) => {
    if (
        zxcvbn(password.value).score <= 2 ||
        password.value !== confirmPassword.value ||
        !validateEmail(emailInput.value || "") ||
        !passwordCombinationCheck(password.value)
    ) {
        e.preventDefault();
    }
});
