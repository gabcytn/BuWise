const withholdingTax = document.querySelector("input#withholding-tax");
updateTotals();

document.querySelectorAll("table input[type='number']").forEach((input) => {
    input.addEventListener("input", updateTotals);
});
withholdingTax.addEventListener("input", updateTotals);

function formatNumber(number, decimals) {
    return Number(number).toLocaleString("en-US", {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    });
}

function updateTotals() {
    let overall = 0;
    const invoiceRow = document.querySelectorAll(".invoice-item");
    invoiceRow.forEach((row, idx) => {
        const key = idx + 1;
        const quantity = row.querySelector(`input[name='qty_${key}']`);
        const unitPrice = row.querySelector(`input[name='unit_price_${key}']`);
        const discount = row.querySelector(`input[name='discount_${key}']`);
        const tax = row.querySelector(`input[name='tax_${key}']`);
        const rowTotal = row.querySelector(`#row-total-${key}`);

        let runningAmount =
            parseFloat(unitPrice.value || 0) -
            parseFloat(discount.value || 0) +
            parseFloat(tax.value || 0);
        runningAmount *= parseFloat(quantity.value || 0);

        overall += runningAmount;
        rowTotal.textContent = formatNumber(runningAmount, 2);
    });
    const withholdingTaxFloat = parseFloat(
        parseFloat(withholdingTax.value || 0).toFixed(2),
    );
    overall -= withholdingTaxFloat;

    document.querySelector("#overall-total strong").textContent = formatNumber(
        overall,
        2,
    );
}
