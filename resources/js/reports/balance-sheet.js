document.addEventListener("DOMContentLoaded", () => {
    const assetsTotal = calculateTotalAssets();
    const liabilitiesTotal = calculateTotalLiabilities();
    const equitiesTotal = calculateTotalEquities();

    document.querySelector(".assets-total strong").textContent = formatNumber(
        assetsTotal,
        2,
    );
    document.querySelector(".liabilities-total").textContent = formatNumber(
        liabilitiesTotal,
        2,
    );
    document.querySelector(".equities-total").textContent = formatNumber(
        equitiesTotal,
        2,
    );

    document.querySelector("#net").textContent = formatNumber(
        liabilitiesTotal + equitiesTotal,
        2,
    );
});

function calculateTotalAssets() {
    const assets = document.querySelectorAll(".assets");
    let assetsTotal = 0;

    assets.forEach((asset) => {
        assetsTotal += parseFloat(asset.textContent.replaceAll(",", ""));
    });

    return assetsTotal;
}

function calculateTotalLiabilities() {
    const liabilities = document.querySelectorAll(".liabilities");
    let liabilitiesTotal = 0;

    liabilities.forEach((liability) => {
        liabilitiesTotal += parseFloat(
            liability.textContent.replaceAll(",", ""),
        );
    });

    return liabilitiesTotal;
}

function calculateTotalEquities() {
    const equities = document.querySelectorAll(".equities");
    let equitiesTotal = 0;

    equities.forEach((equity) => {
        equitiesTotal += parseFloat(equity.textContent.replaceAll(",", ""));
    });

    return equitiesTotal;
}

function formatNumber(number, decimals) {
    return Number(number).toLocaleString("en-US", {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    });
}

document.querySelector("#download-table-btn").addEventListener("click", () => {
    const csvData = gatherTableData();
    downloadCSVFile(csvData);
});

function gatherTableData() {
    let csvData = [];
    const rows = document.querySelectorAll(".report-body__table tr");
    rows.forEach((row) => {
        const columns = row.querySelectorAll("td, th");
        const value = columns[1].innerText.replaceAll(",", "");
        csvData.push([columns[0].innerText, value].join(",", 1));
    });
    return csvData.join("\n");
}
function downloadCSVFile(csv_data) {
    const CSVFile = new Blob([csv_data], { type: "text/csv" });
    let url = window.URL.createObjectURL(CSVFile);

    const tempLink = document.createElement("a");
    tempLink.download = "balance-sheet.csv";
    tempLink.href = url;
    tempLink.style.display = "block";
    tempLink.style.fontSize = "5rem";

    document.body.appendChild(tempLink);

    tempLink.click();
    document.body.removeChild(tempLink);
}

document.querySelectorAll(".clickable").forEach((item) => {
    item.addEventListener("click", () => {
        location.href = item.dataset.redirect;
    });
});
