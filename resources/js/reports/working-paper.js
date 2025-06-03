document.addEventListener("DOMContentLoaded", () => {
    const rows = document.querySelectorAll(".clickable");
    let totalDebit = 0;
    let totalCredit = 0;
    rows.forEach((row) => {
        const dr = row.querySelector(".debit").innerText.replaceAll(",", "");
        const cr = row.querySelector(".credit").innerText.replaceAll(",", "");
        totalDebit += parseFloat(dr);
        totalCredit += parseFloat(cr);

        const initial = formatNumber(
            Math.abs(parseFloat(dr) - parseFloat(cr)),
            2,
        );
        const type = parseFloat(dr) > parseFloat(cr) ? " Dr" : " Cr";
        row.querySelector(".row-total").innerText = `${initial} ${type}`;
    });

    document.querySelector("#net-debit").innerText = formatNumber(
        totalDebit,
        2,
    );
    document.querySelector("#net-credit").innerText = formatNumber(
        totalCredit,
        2,
    );

    const initial = formatNumber(Math.abs(totalDebit - totalCredit), 2);
    const type = totalDebit > totalCredit ? " Dr" : " Cr";
    document.querySelector("#net").innerText = `${initial} ${type}`;
});
function formatNumber(number, decimals) {
    return Number(number).toLocaleString("en-US", {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    });
}

// CSV functionality
document.querySelector("#download-table-btn").addEventListener("click", () => {
    const csvData = gatherTableData();
    downloadCSVFile(csvData);
});

function gatherTableData() {
    let csvData = [];
    const rows = document.querySelectorAll(".report-body__table tr");
    rows.forEach((row) => {
        const columns = row.querySelectorAll("td, th");
        const debit = columns[2].innerText.replaceAll(",", "");
        const credit = columns[3].innerText.replaceAll(",", "");
        const balance = columns[4].innerText.replaceAll(",", "");
        csvData.push(
            [
                columns[0].innerText,
                columns[1].innerText,
                debit,
                credit,
                balance,
            ].join(",", 1),
        );
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
    tempLink.download = `working-paper_${y}-${m}-${d}_${t}.csv`;
    tempLink.href = url;
    tempLink.style.display = "block";
    tempLink.style.fontSize = "5rem";

    document.body.appendChild(tempLink);

    tempLink.click();
    document.body.removeChild(tempLink);
}
