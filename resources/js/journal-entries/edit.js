const amountInputs = document.querySelectorAll(
    "#journalBody input[type='number']",
);
const totalDebits = document.querySelector("#totalDebits");
const totalCredits = document.querySelector("#totalCredits");
const submitBtn = document.querySelector("#submitButton");
const balanceWarning = document.querySelector("#balanceWarning");

let debitAmount = 0;
let creditAmount = 0;
amountInputs.forEach((input) => {
    if (input.value !== "" && input.name.startsWith("debit")) {
        debitAmount += parseFloat(input.value);
    } else if (input.value !== "" && input.name.startsWith("credit")) {
        creditAmount += parseFloat(input.value);
    }
    input.addEventListener("input", (e) => {
        if (e.target.name.startsWith("debit_")) {
            const creditInput =
                e.target.parentNode.nextElementSibling.querySelector("input");
            if (!e.target.value) {
                creditInput.disabled = false;
            } else {
                creditInput.disabled = true;
            }
        } else if (e.target.name.startsWith("credit_")) {
            const debitInput =
                e.target.parentNode.previousElementSibling.querySelector(
                    "input",
                );
            if (!e.target.value) {
                debitInput.disabled = false;
            } else {
                debitInput.disabled = true;
            }
        }
        updateTotals();
    });
});

totalDebits.textContent = debitAmount;
totalCredits.textContent = creditAmount;

if (debitAmount !== creditAmount) {
    submitBtn.disabled = true;
    balanceWarning.style.display = "block";
}

function updateTotals() {
    let debitTotal = 0;
    let creditTotal = 0;
    amountInputs.forEach((input) => {
        if (
            input.name &&
            input.name.startsWith("debit_") &&
            !input.disabled &&
            input.value
        ) {
            debitTotal += parseFloat(input.value || 0);
        } else if (
            input.name &&
            input.name.startsWith("credit_") &&
            !input.disabled &&
            input.value
        ) {
            creditTotal += parseFloat(input.value || 0);
        }
    });
    totalDebits.textContent = debitTotal;
    totalCredits.textContent = creditTotal;

    if (debitTotal !== creditTotal) {
        submitBtn.disabled = true;
        balanceWarning.style.display = "block";
        totalDebits.style.color = "red";
        totalCredits.style.color = "red";
    } else {
        submitBtn.disabled = false;
        balanceWarning.style.display = "none";
        totalDebits.style.color = "var(--soft-black)";
        totalCredits.style.color = "var(--soft-black)";
    }
}
