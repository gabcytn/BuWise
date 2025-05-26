const amountInputs = document.querySelectorAll(
    "#journalBody input[type='number']",
);
const addNewRowBtn = document.querySelector(".add-row-btn");

document.querySelectorAll(".tax-select").forEach((select) => {
    select.addEventListener("change", updateTotals);
});

updateTotals();
function updateTotals() {
    let totalDebits = 0;
    let totalCredits = 0;
    let actualD = 0;
    let actualC = 0;

    const journalRows = document.querySelectorAll(".journal-row");
    journalRows.forEach((row, idx) => {
        const taxSelect = row.querySelector(`select[name='tax_${idx + 1}']`);
        const debitInput = row.querySelector(`input[name='debit_${idx + 1}']`);
        const creditInput = row.querySelector(
            `input[name='credit_${idx + 1}']`,
        );

        if (!debitInput.disabled && debitInput.value) {
            totalDebits += parseFloat(debitInput.value || 0);
            actualD += parseFloat(debitInput.value || 0);
            const taxSelectedValue =
                taxSelect[taxSelect.selectedIndex].dataset.taxValue;
            if (taxSelectedValue !== "no_tax") {
                const percentage = parseFloat(taxSelectedValue) / 100;
                actualD += parseFloat(debitInput.value * percentage);
            }
        } else if (!creditInput.disabled && creditInput.value) {
            totalCredits += parseFloat(creditInput.value || 0);
            actualC += parseFloat(creditInput.value || 0);
            const taxSelectedValue =
                taxSelect[taxSelect.selectedIndex].dataset.taxValue;
            if (taxSelectedValue !== "no_tax") {
                const percentage = parseFloat(taxSelectedValue) / 100;
                actualC += parseFloat(creditInput.value * percentage);
            }
        }
    });

    // Update totals display
    document.querySelector(".subtotals-row #totalDebits div").textContent =
        totalDebits.toFixed(2);
    document.querySelector(".subtotals-row #totalCredits div").textContent =
        totalCredits.toFixed(2);

    document.querySelector("#actual-total-debits").textContent =
        actualD.toFixed(2);
    document.querySelector("#actual-total-credits").textContent =
        actualC.toFixed(2);

    // Enable/disable submit button based on balance
    const submitButton = document.getElementById("submitButton");
    const balanceWarning = document.getElementById("balanceWarning");

    const actualTotalDebits = parseFloat(
        document.querySelector("#actual-total-debits").textContent,
    );
    const actualTotalCredits = parseFloat(
        document.querySelector("#actual-total-credits").textContent,
    );

    if (actualD === actualC && actualD > 0) {
        submitButton.disabled = false;
        balanceWarning.style.display = "none";
        document.querySelector(".totals-row").style.color = "black";
    } else {
        submitButton.disabled = true;
        if (actualD > 0 || actualC > 0) {
            balanceWarning.style.display = "inline";
            document.querySelector(".totals-row").style.color = "red";
        } else {
            balanceWarning.style.display = "none";
            document.querySelector(".totals-row").style.color = "black";
        }
    }
}

addNewRowBtn.addEventListener("click", () => {
    addRow();
});

let rowCounter = document.querySelector("#journalForm").dataset.rowCount;
function addRow() {
    rowCounter++;
    const tbody = document.getElementById("journalBody");
    const newRow = document.createElement("tr");
    newRow.classList = "journal-row";

    // Account dropdown
    const accountCell = document.createElement("td");
    const accountSelect = document.querySelector("select#select-clone");
    const accountSelectClone = accountSelect.cloneNode(true);
    accountSelectClone.style.display = "block";
    accountSelectClone.name = `account_${rowCounter}`;

    accountCell.appendChild(accountSelectClone);

    // Description field
    const descriptionCell = document.createElement("td");
    const descriptionInput = document.createElement("input");
    descriptionInput.placeholder = "Description";
    descriptionInput.classList.add("row-description");

    descriptionCell.appendChild(descriptionInput);

    // Tax field
    const taxCell = document.createElement("td");
    const taxSelect = document.querySelector("select#tax-select-clone");
    const taxSelectClone = taxSelect.cloneNode(true);
    taxSelectClone.style.display = "block";
    taxSelectClone.name = `tax_${rowCounter}`;

    taxSelectClone.addEventListener("change", updateTotals);

    taxCell.appendChild(taxSelectClone);

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
        const journalRows = document.querySelectorAll(".journal-row");
        if (journalRows.length <= 2) {
            return;
        }

        tbody.removeChild(newRow);
        updateTotals();
    };
    deleteCell.appendChild(deleteBtn);

    // Append all cells to the row
    newRow.appendChild(accountCell);
    newRow.appendChild(descriptionCell);
    newRow.appendChild(taxCell);
    newRow.appendChild(debitCell);
    newRow.appendChild(creditCell);
    newRow.appendChild(deleteCell);

    // Add the row to the table
    tbody.appendChild(newRow);
}
