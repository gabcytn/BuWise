const amountInputs = document.querySelectorAll(
    "#journalBody input[type='number']",
);
const totalDebits = document.querySelector("#totalDebits");
const totalCredits = document.querySelector("#totalCredits");
const submitBtn = document.querySelector("#submitButton");
const balanceWarning = document.querySelector("#balanceWarning");
const addNewRowBtn = document.querySelector(".add-row-btn");

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

totalDebits.textContent = debitAmount.toFixed(2);
totalCredits.textContent = creditAmount.toFixed(2);

if (debitAmount !== creditAmount) {
    submitBtn.disabled = true;
    balanceWarning.style.display = "block";
}

function updateTotals() {
    let debitTotal = 0;
    let creditTotal = 0;
    const amountInputs = document.querySelectorAll(
        "#journalBody input[type='number']",
    );
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
    totalDebits.textContent = debitTotal.toFixed(2);
    totalCredits.textContent = creditTotal.toFixed(2);

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

addNewRowBtn.addEventListener("click", () => {
    addRow();
});

// TODO: dynamic row count
let rowCounter = document.querySelector("#journalForm").dataset.rowCount;
function addRow() {
    rowCounter++;
    const tbody = document.getElementById("journalBody");
    const newRow = document.createElement("tr");

    // Account dropdown
    const accountCell = document.createElement("td");
    const accountSelect = document.querySelector("#select-clone");
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
