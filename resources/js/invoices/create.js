document.querySelector(".btn.add-row").addEventListener("click", addRow);

document.addEventListener("DOMContentLoaded", () => {
    addRow();
});

let rowCount = 0;
function addRow() {
    rowCount++;

    const tableBody = document.querySelector("#table-body");
    const newRow = document.createElement("tr");

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
    const discountSelect = document.querySelector(
        "select[name='discount_type']",
    );
    const discountSelectClone = discountSelect.cloneNode(true);
    discountSelectClone.name = `discount_${rowCount}`;
    discountSelectClone.addEventListener("change", updateTotals);

    discountCell.appendChild(discountSelectClone);

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
    rows.forEach((row, idx) => {
        let finalAmount = 0;
        const qty = row.querySelector(`input[name='qty_${idx + 1}']`);
        const unitPrice = row.querySelector(
            `input[name='unit_price_${idx + 1}']`,
        );
        const netAmount = row.querySelector(`#net_amount_${idx + 1}`);
        const discountSelect = row.querySelector(
            `select[name='discount_${idx + 1}']`,
        );
        const taxSelect = row.querySelector(`select[name='tax_${idx + 1}']`);
        const totalAmount = row.querySelector(`#total_${idx + 1}`);

        const qtyFloat = parseFloat(qty.value || 0);
        const unitPriceFloat = parseFloat(unitPrice.value || 0);
        netAmount.textContent = (qtyFloat * unitPriceFloat).toFixed(2);
        finalAmount = (qtyFloat * unitPriceFloat).toFixed(2);

        switch (discountSelect[discountSelect.selectedIndex].value) {
            case "senior_citizen":
            case "pwd":
                finalAmount = (
                    parseFloat(finalAmount) -
                    parseFloat(finalAmount) * 0.2
                ).toFixed(2);
                break;
            default:
                break;
        }

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
