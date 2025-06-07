document
    .querySelector(".insights-header form")
    .addEventListener("submit", (e) => {
        e.preventDefault();
        const selectClient = document.querySelector("select[name='client']");
        const client = selectClient[selectClient.selectedIndex].value;
        getCashFlowData(client);
    });
async function getCashFlowData(clientId) {
    const res = await fetch(`/cash-flow/${clientId}`);
    const data = await res.json();

    let inflowsTotal = 0;
    let outflowsTotal = 0;

    const months = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
    ];

    const inflows = new Array(12).fill(0);
    const outflows = new Array(12).fill(0);

    data.forEach((item) => {
        const month = getMonthFromDate(item.date);
        switch (item.entry_type) {
            case "debit":
                inflowsTotal += item.amount;
                inflows[month - 1] += item.amount;
                break;
            case "credit":
                outflowsTotal += item.amount;
                outflows[month - 1] += item.amount;
                break;
            default:
                break;
        }
    });

    console.log(months);
    console.log(inflows);
    console.log(outflows);
    start(months, inflows, outflows);
    updateCashFlowSummary(inflows, outflows);
    document.querySelector(".insights-body.d-none").classList.remove("d-none");
}

function updateCashFlowSummary(inflows, outflows) {
    const inflowsTotal = inflows.reduce((a, b) => a + b, 0);
    const outflowsTotal = outflows.reduce((a, b) => a + b, 0);

    document.querySelector(".inflows p").textContent = formatNumber(
        inflowsTotal,
        2,
    );
    document.querySelector(".outflows p").textContent = formatNumber(
        outflowsTotal,
        2,
    );
}

function getMonthFromDate(dateStr) {
    const month = new Date(dateStr).getMonth() + 1;
    return month;
}

function start(months, inflows, outflows) {
    const cashFlowCanvas = document.querySelector("#cash-flow__body--canvas");
    const myChart = new Chart(cashFlowCanvas, {
        type: "bar",
        data: {
            labels: months,
            datasets: [
                {
                    label: "Inflows",
                    data: inflows,
                    backgroundColor: "rgba(0, 255, 0, 0.3)",
                },
                {
                    label: "Outflows",
                    data: outflows,
                    backgroundColor: "rgba(0, 0, 0, 0.3)",
                },
            ],
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
        },
    });
}

function formatNumber(number, decimals) {
    return Number(number).toLocaleString("en-US", {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    });
}
