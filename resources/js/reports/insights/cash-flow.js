export const months = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "May",
    "Jun",
    "Jul",
    "Aug",
    "Sep",
    "Oct",
    "Nov",
    "Dec",
];
export async function getCashFlowData(clientId) {
    const res = await fetch(`/cash-flow/${clientId}`);
    const data = await res.json();

    let inflowsTotal = 0;
    let outflowsTotal = 0;

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

    start(months, inflows, outflows);
    updateCashFlowSummary(inflows, outflows);
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

let myChart;
function start(months, inflows, outflows) {
    const cashFlowCanvas = document.querySelector("#cash-flow__body--canvas");
    if (myChart !== undefined) myChart.destroy();
    myChart = new Chart(cashFlowCanvas, {
        type: "bar",
        data: {
            labels: months,
            datasets: [
                {
                    label: "Inflows",
                    data: inflows,
                    backgroundColor: "rgba(255, 197, 132, 255)",
                },
                {
                    label: "Outflows",
                    data: outflows,
                    backgroundColor: "rgba(170, 91, 213, 255)",
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
