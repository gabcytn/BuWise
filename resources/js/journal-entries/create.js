document.querySelector(".add-row-btn").addEventListener("click", () => {
    addRow();
});

let rowCounter = 0; // Used to create unique names for form elements

// Add initial two rows
document.addEventListener("DOMContentLoaded", () => {
    addRow(); // Debit row
    addRow(); // Credit row

    // Add form submission handling
    document
        .getElementById("journalForm")
        .addEventListener("submit", (event) => {
            if (!validateForm()) {
                event.preventDefault();
            }
        });
});

function addRow() {
    rowCounter++;
    const tbody = document.getElementById("journalBody");
    const newRow = document.createElement("tr");

    // Account dropdown
    const accountCell = document.createElement("td");
    const accountSelect = document.querySelector("#select-account");
    const accountSelectClone = accountSelect.cloneNode(true);
    accountSelectClone.style.display = "block";
    accountSelectClone.name = `account_${rowCounter}`;

    accountCell.appendChild(accountSelectClone);

    // Debits field
    const debitCell = document.createElement("td");
    const debitInput = document.createElement("input");
    debitInput.type = "number";
    debitInput.min = "0";
    debitInput.step = "0.01";
    debitInput.placeholder = "0.00";
    debitInput.name = `debit_${rowCounter}`;
    debitInput.addEventListener("input", (e) => {
        // If debit has value, disable credit field
        const creditInput =
            e.target.parentNode.nextElementSibling.querySelector("input");
        if (e.target.value && e.target.value > 0) {
            creditInput.disabled = true;
            creditInput.value = "";
        } else {
            creditInput.disabled = false;
        }
        updateTotals();
    });
    debitCell.appendChild(debitInput);

    // Credits field
    const creditCell = document.createElement("td");
    const creditInput = document.createElement("input");
    creditInput.type = "number";
    creditInput.min = "0";
    creditInput.step = "0.01";
    creditInput.placeholder = "0.00";
    creditInput.name = `credit_${rowCounter}`;
    creditInput.addEventListener("input", (e) => {
        // If credit has value, disable debit field
        const debitInput =
            e.target.parentNode.previousElementSibling.querySelector("input");
        if (e.target.value && e.target.value > 0) {
            debitInput.disabled = true;
            debitInput.value = "";
        } else {
            debitInput.disabled = false;
        }
        updateTotals();
    });
    creditCell.appendChild(creditInput);

    // Hidden row ID field (helpful for server-side processing)
    const rowIdInput = document.createElement("input");
    rowIdInput.type = "hidden";
    rowIdInput.name = `row_id_${rowCounter}`;
    rowIdInput.value = rowCounter;
    creditCell.appendChild(rowIdInput);

    // Delete button
    const deleteCell = document.createElement("td");
    const deleteBtn = document.createElement("button");
    deleteBtn.innerHTML = "×";
    deleteBtn.className = "delete-btn";
    deleteBtn.title = "Remove row";
    deleteBtn.type = "button"; // Prevent form submission on click
    deleteBtn.onclick = () => {
        tbody.removeChild(newRow);
        updateTotals();

        // If fewer than two rows, add new rows to maintain minimum
        if (tbody.children.length < 2) {
            addRow();
            if (tbody.children.length < 2) {
                addRow();
            }
        }
    };
    deleteCell.appendChild(deleteBtn);

    // Append all cells to the row
    newRow.appendChild(accountCell);
    newRow.appendChild(debitCell);
    newRow.appendChild(creditCell);
    newRow.appendChild(deleteCell);

    // Add the row to the table
    tbody.appendChild(newRow);
}

function updateTotals() {
    let totalDebits = 0;
    let totalCredits = 0;

    // Get all debit and credit inputs
    const debitInputs = document.querySelectorAll(
        '#journalBody input[type="number"]',
    );

    debitInputs.forEach((input) => {
        if (
            input.name &&
            input.name.startsWith("debit_") &&
            !input.disabled &&
            input.value
        ) {
            totalDebits += parseFloat(input.value || 0);
        } else if (
            input.name &&
            input.name.startsWith("credit_") &&
            !input.disabled &&
            input.value
        ) {
            totalCredits += parseFloat(input.value || 0);
        }
    });

    // Update totals display
    document.getElementById("totalDebits").textContent = totalDebits.toFixed(2);
    document.getElementById("totalCredits").textContent =
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

function validateForm() {
    // Check if there are at least two rows with data
    const filledRows = Array.from(
        document.querySelectorAll("#journalBody tr"),
    ).filter((row) => {
        const account = row.querySelector('select[name^="account_"]').value;
        const hasDebit = row.querySelector('input[name^="debit_"]').value > 0;
        const hasCredit = row.querySelector('input[name^="credit_"]').value > 0;
        return account && (hasDebit || hasCredit);
    });

    if (filledRows.length < 2) {
        alert(
            "You need at least two entries (one debit and one credit) to submit a journal entry.",
        );
        return false;
    }

    // Check if debits and credits are balanced
    const totalDebits = parseFloat(
        document.getElementById("totalDebits").textContent,
    );
    const totalCredits = parseFloat(
        document.getElementById("totalCredits").textContent,
    );

    if (totalDebits !== totalCredits) {
        alert("Debits and credits must be equal before submitting.");
        return false;
    }

    return true;
}
