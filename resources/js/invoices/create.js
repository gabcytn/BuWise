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

    const netAmountCell = document.createElement("td");
    netAmountCell.id = `net_amount_${rowCount}`;
    netAmountCell.textContent = "0.00";

    const discountCell = document.createElement("td");
    const discountInput = document.querySelector("#discount");
    const discountInputClone = discountInput.cloneNode(true);
    discountInputClone.name = `discount_${rowCount}`;
    discountInputClone.addEventListener("input", updateTotals);

    discountCell.appendChild(discountInputClone);

    const taxCell = document.createElement("td");
    const taxSelect = document.querySelector("select[name='tax']");
    const taxSelectClone = taxSelect.cloneNode(true);
    taxSelectClone.addEventListener("change", updateTotals);
    taxSelectClone.name = `tax_${rowCount}`;

    taxCell.appendChild(taxSelectClone);

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
    newRow.appendChild(netAmountCell);
    newRow.appendChild(discountCell);
    newRow.appendChild(taxCell);
    newRow.appendChild(totalAmountCell);
    newRow.appendChild(deleteCell);

    tableBody.appendChild(newRow);
}

function updateTotals() {
    const rows = document.querySelectorAll("#table-body tr");

    let sum = 0;
    rows.forEach((row) => {
        const key = row.dataset.rowNumber;
        let finalAmount = 0;
        const qty = row.querySelector(`input[name='qty_${key}']`);
        const unitPrice = row.querySelector(`input[name='unit_price_${key}']`);
        const netAmount = row.querySelector(`#net_amount_${key}`);
        const discountInput = row.querySelector(
            `input[name='discount_${key}']`,
        );
        const taxSelect = row.querySelector(`select[name='tax_${key}']`);
        const totalAmount = row.querySelector(`#total_${key}`);

        const qtyFloat = parseFloat(qty.value || 0);
        const unitPriceFloat = parseFloat(unitPrice.value || 0);
        netAmount.textContent = (qtyFloat * unitPriceFloat).toFixed(2);
        finalAmount = (qtyFloat * unitPriceFloat).toFixed(2);

        finalAmount = (
            parseFloat(finalAmount) -
            parseFloat(finalAmount) * parseFloat(discountInput.value / 100)
        ).toFixed(2);

        const taxValue = parseFloat(
            taxSelect[taxSelect.selectedIndex].dataset.taxValue,
        );
        if (taxValue !== 0) {
            const percentage = taxValue / 100;
            const taxed = parseFloat(finalAmount) * percentage;
            finalAmount = (parseFloat(finalAmount) + taxed).toFixed(2);
        }
        totalAmount.textContent = finalAmount;
        sum += parseFloat(finalAmount);
    });

    document.querySelector("#total-sum").textContent = sum.toFixed(2);
}

const transactionType = document.querySelector(
    "select[name='transaction_type']",
);
const customerSupplierLabel = document.querySelector(
    "#customer-supplier-input label",
);
const customerSupplierInput = document.querySelector(
    "#customer-supplier-input input",
);
transactionType.addEventListener("change", (e) => {
    switch (transactionType[transactionType.selectedIndex].value) {
        case "1":
            customerSupplierLabel.textContent = "Name of Customer";
            customerSupplierInput.placeholder = "Enter Name of Customer";
            customerSupplierInput.name = "customer";
            break;
        case "2":
            customerSupplierLabel.textContent = "Name of Supplier";
            customerSupplierInput.placeholder = "Enter Name of Supplier";
            customerSupplierInput.name = "supplier";
            break;
        default:
            break;
    }
});
