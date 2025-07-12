const emailInput = document.querySelector("input#email");
const emailFeedback = document.querySelector("span#email-feedback");

emailInput.addEventListener("input", (e) => {
    const val = e.target.value;

    if (validateEmail(val)) {
        emailFeedback.style.display = "none";
        emailInput.style.color = "#020202";
        return;
    }
    if (val) {
        emailFeedback.style.display = "inline";
        emailInput.style.color = "red";
    } else {
        emailFeedback.style.display = "none";
        emailInput.style.color = "#020202";
    }
});

const contactForm = document.querySelector("form#contact-form");
contactForm.addEventListener("submit", (e) => {
    e.preventDefault();
    if (validateEmail(emailInput.value)) {
        contactForm.submit();
    }
});
const validateEmail = (email) => {
    return email.match(
        /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
    );
};
