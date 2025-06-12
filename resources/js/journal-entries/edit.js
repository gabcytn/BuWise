const amountInputs = document.querySelectorAll(
    "#journalBody input[type='number']",
);

document.querySelectorAll(".tax-select").forEach((select) => {
    select.addEventListener("change", updateTotals);
});

let debitAmount = 0;
let creditAmount = 0;

amountInputs.forEach((input) => {
    if (input.value !== "" && input.name.startsWith("debit")) {
        debitAmount += parseFloat(input.value || 0);
    } else if (input.value !== "" && input.name.startsWith("credit")) {
        creditAmount += parseFloat(input.value || 0);
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

updateTotals();

function updateTotals() {
    let totalDebits = 0;
    let totalCredits = 0;

    const journalRows = document.querySelectorAll(".journal-row");
    journalRows.forEach((row, idx) => {
        const debitInput = row.querySelector(`input[name='debit_${idx + 1}']`);
        const creditInput = row.querySelector(
            `input[name='credit_${idx + 1}']`,
        );

        if (!debitInput.disabled && debitInput.value)
            totalDebits += parseFloat(debitInput.value || 0);
        else if (!creditInput.disabled && creditInput.value)
            totalCredits += parseFloat(creditInput.value || 0);
    });

    document.querySelector("#actual-total-debits").textContent =
        totalDebits.toFixed(2);
    document.querySelector("#actual-total-credits").textContent =
        totalCredits.toFixed(2);

    // Enable/disable submit button based on balance
    const submitButton = document.getElementById("submitButton");
    const balanceWarning = document.getElementById("balanceWarning");

    if (totalDebits === totalCredits && totalDebits > 0) {
        submitButton.disabled = false;
        balanceWarning.style.display = "none";
        document.querySelector(".totals-row").style.color = "black";
    } else {
        submitButton.disabled = true;
        if (totalDebits > 0 || totalCredits > 0) {
            balanceWarning.style.display = "inline";
            document.querySelector(".totals-row").style.color = "red";
        } else {
            balanceWarning.style.display = "none";
            document.querySelector(".totals-row").style.color = "black";
        }
    }
}
