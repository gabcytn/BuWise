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
    const discountInput = document.querySelector("#discount");
    const discountInputClone = discountInput.cloneNode(true);
    discountInputClone.name = `discount_${rowCount}`;
    discountInputClone.addEventListener("input", updateTotals);
    discountInputClone.id = "";
    discountInputClone.classList.remove("d-none");

    discountCell.appendChild(discountInputClone);

    const taxCell = document.createElement("td");
    const taxSelect = document.querySelector("select[name='tax']");
    const taxSelectClone = taxSelect.cloneNode(true);
    taxSelectClone.addEventListener("change", updateTotals);
    taxSelectClone.name = `tax_${rowCount}`;
    taxSelectClone.classList.remove("d-none");

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
        let runningAmount = 0;
        const qty = row.querySelector(`input[name='qty_${key}']`);
        const unitPrice = row.querySelector(`input[name='unit_price_${key}']`);
        const discountInput = row.querySelector(
            `input[name='discount_${key}']`,
        );
        const taxSelect = row.querySelector(`select[name='tax_${key}']`);
        const totalAmount = row.querySelector(`#total_${key}`);

        const qtyFloat = parseFloat(qty.value || 0);
        const unitPriceFloat = parseFloat(unitPrice.value || 0);
        runningAmount = unitPriceFloat;

        if (
            discountInput.value &&
            typeof parseFloat(discountInput.value) === "number"
        ) {
            // get the remaining percentage of subtotal after subtracting discount;
            const remainingPercentage =
                (100 - parseFloat(discountInput.value)) / 100;
            runningAmount = (
                parseFloat(runningAmount) * remainingPercentage
            ).toFixed(2);
        }

        const taxValue = parseFloat(
            taxSelect[taxSelect.selectedIndex].dataset.taxValue,
        );
        if (typeof taxValue === "number" && taxValue !== 0) {
            const percentage = taxValue / 100;
            const taxed = parseFloat(runningAmount) * percentage;
            runningAmount = (parseFloat(runningAmount) + taxed).toFixed(2);
        }

        const finalAnswer = (runningAmount * qtyFloat).toFixed(2);
        totalAmount.textContent = finalAnswer;
        sum += parseFloat(finalAnswer);
    });

    document.querySelector("#total-sum").textContent = sum.toFixed(2);
}
