document.querySelector(".btn.add-row").addEventListener("click", addRow);

document.addEventListener("DOMContentLoaded", () => {
    addRow();
});

let rowCount = 0;
function addRow() {
    rowCount++;

    const tableBody = document.querySelector("#table-body");
    const newRow = document.createElement("tr");
    newRow.dataset.rowNumber = rowCount;

    const itemNameCell = document.createElement("td");
    const itemNameInput = document.createElement("input");
    itemNameInput.name = `item_${rowCount}`;
    itemNameInput.placeholder = "Item Name";
    itemNameInput.required = true;

    itemNameCell.appendChild(itemNameInput);

    const qtyCell = document.createElement("td");
    const qtyInput = document.createElement("input");
    qtyInput.name = `qty_${rowCount}`;
    qtyInput.type = "number";
    qtyInput.placeholder = "0";
    qtyInput.required = true;
    qtyInput.addEventListener("input", updateTotals);

    qtyCell.appendChild(qtyInput);

    const unitPriceCell = document.createElement("td");
    const unitPriceInput = document.createElement("input");
    unitPriceInput.name = `unit_price_${rowCount}`;
    unitPriceInput.placeholder = "0.00";
    unitPriceInput.type = "number";
    unitPriceInput.step = "0.01";
    unitPriceInput.required = true;
    unitPriceInput.addEventListener("input", updateTotals);

    unitPriceCell.appendChild(unitPriceInput);

    const discountCell = document.createElement("td");
    const discountInput = document.createElement("input");
    discountInput.name = `discount_${rowCount}`;
    discountInput.addEventListener("input", updateTotals);
    discountInput.placeholder = "0.00";
    discountInput.type = "number";
    discountInput.step = "0.01";

    discountCell.appendChild(discountInput);

    const taxCell = document.createElement("td");
    const taxInput = document.createElement("input");
    taxInput.addEventListener("input", updateTotals);
    taxInput.name = `tax_${rowCount}`;
    taxInput.type = "number";
    taxInput.step = "0.01";
    taxInput.placeholder = "0.00";

    taxCell.appendChild(taxInput);

    const totalAmountCell = document.createElement("td");
    totalAmountCell.id = `total_${rowCount}`;
    totalAmountCell.textContent = "0.00";

    const deleteCell = document.createElement("td");
    const deleteButton = document.createElement("button");
    deleteButton.type = "button";
    deleteButton.textContent = "x";
    deleteButton.addEventListener("click", () => {
        const rows = document.querySelectorAll("#table-body tr");
        if (rows.length === 1) return;
        tableBody.removeChild(newRow);
        updateTotals();
    });

    deleteCell.appendChild(deleteButton);

    newRow.appendChild(itemNameCell);
    newRow.appendChild(qtyCell);
    newRow.appendChild(unitPriceCell);
    newRow.appendChild(discountCell);
    newRow.appendChild(taxCell);
    newRow.appendChild(totalAmountCell);
    newRow.appendChild(deleteCell);

    tableBody.appendChild(newRow);
}

const withholdingTax = document.querySelector("input#withholding-tax");
withholdingTax.addEventListener("input", updateTotals);

function updateTotals() {
    const rows = document.querySelectorAll("#table-body tr");

    let sum = 0;
    rows.forEach((row) => {
        const key = row.dataset.rowNumber;
        let runningAmount = 0;
        const qty = row.querySelector(`input[name='qty_${key}']`);
        const unitPrice = row.querySelector(`input[name='unit_price_${key}']`);
        const discountInput = row.querySelector(
            `input[name='discount_${key}']`,
        );
        const taxInput = row.querySelector(`input[name='tax_${key}']`);
        const totalAmount = row.querySelector(`#total_${key}`);

        const qtyFloat = parseFloat(qty.value || 0);
        const unitPriceFloat = parseFloat(unitPrice.value || 0);
        const taxFloat = parseFloat(taxInput.value || 0);
        const discountFloat = parseFloat(discountInput.value || 0);

        runningAmount = unitPriceFloat;

        if (discountFloat && discountFloat > 0) {
            runningAmount = (parseFloat(runningAmount) - discountFloat).toFixed(
                2,
            );
        }

        if (taxFloat && taxFloat > 0) {
            runningAmount = (parseFloat(runningAmount) + taxFloat).toFixed(2);
        }

        const withholdingTaxFloat = parseFloat(withholdingTax.value || 0);
        const finalAnswer = (
            runningAmount * qtyFloat -
            withholdingTaxFloat
        ).toFixed(2);
        totalAmount.textContent = finalAnswer;
        sum += parseFloat(finalAnswer);
    });

    document.querySelector("#total-sum").textContent = sum.toFixed(2);
}

// receivable/payable switch
const paymentMethodSelect = document.querySelector(
    "select[name='payment_method']",
);
const transactionTypeSelect = document.querySelector(
    "select[name='transaction_type']",
);

transactionTypeSelect.addEventListener("change", updatePaymentLabel);

updatePaymentLabel();
function updatePaymentLabel() {
    const paymentMethodLabel = document.querySelector("#payment-method");
    switch (transactionTypeSelect[transactionTypeSelect.selectedIndex].value) {
        case "purchases":
            paymentMethodLabel.textContent = "Credit To";
            withholdingTax.parentElement.style.display = "none";
            break;
        case "sales":
            paymentMethodLabel.textContent = "Debit To";
            withholdingTax.parentElement.style.display = "";
            break;
        default:
            break;
    }
}
