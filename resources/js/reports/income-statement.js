document.addEventListener("DOMContentLoaded", () => {
    const revenueTotal = calculateSum(".revenues");
    document.querySelector(".revenues-total").innerText = formatNumber(
        revenueTotal,
        2,
    );

    const expenseTotal = calculateSum(".expenses");
    document.querySelector(".expenses-total").innerText = formatNumber(
        expenseTotal,
        2,
    );

    document.querySelector("#net").innerText = formatNumber(
        revenueTotal - expenseTotal,
        2,
    );
});

function calculateSum(className) {
    const list = document.querySelectorAll(className);
    let total = 0;

    list.forEach((item) => {
        const currentValue = parseFloat(item.textContent.replaceAll(",", ""));
        total += currentValue;
        if (currentValue < 0) item.style.color = "red";
    });

    return total;
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
    const date = new Date();
    const m = date.getMonth();
    const d = date.getDate();
    const y = date.getFullYear();
    const t = date.getTime();
    tempLink.download = `income-statement_${y}-${m}-${d}_${t}.csv`;
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
