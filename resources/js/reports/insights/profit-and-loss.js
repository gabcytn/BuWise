export async function getPLData(clientId) {
    const res = await fetch(`/profit-and-loss/${clientId}`);
    const data = await res.json();

    const revenues = new Array(12).fill(0);
    const expenses = new Array(12).fill(0);
    const net = new Array(12).fill(0);

    data.forEach((item) => {
        const idx = getMonthFromDate(item.date);
        switch (item.acc_group) {
            case "Revenue":
                switch (item.entry_type) {
                    case "debit":
                        revenues[idx] -= item.amount;
                        break;
                    case "credit":
                        revenues[idx] += item.amount;
                        break;
                    default:
                        break;
                }
                break;
            case "Expenses":
                switch (item.entry_type) {
                    case "debit":
                        expenses[idx] += item.amount;
                        break;
                    case "credit":
                        expenses[idx] -= item.amount;
                        break;
                    default:
                        break;
                }
                break;
            default:
                break;
        }
    });
    for (let i = 0; i < net.length; i++) {
        net[i] = revenues[i] - expenses[i];
    }
    start(revenues, expenses, net);
    updatePLUi(revenues, expenses, net);
}

function updatePLUi(revenues, expenses, net) {
    document.querySelector(".pl-revenue .card-value").textContent =
        formatNumber(
            revenues.reduce((a, b) => a + b, 0),
            2,
        );
    document.querySelector(".pl-expenses .card-value").textContent =
        formatNumber(
            expenses.reduce((a, b) => a + b, 0),
            2,
        );
    document.querySelector(".pl-profit .card-value").textContent = formatNumber(
        net.reduce((a, b) => a + b, 0),
        2,
    );
}

function formatNumber(number, decimals) {
    return Number(number).toLocaleString("en-US", {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    });
}

function getMonthFromDate(dateStr) {
    const month = new Date(dateStr).getMonth();
    return month;
}

let myChart;
function start(revenues, expenses, net) {
    const data = {
        labels: [
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
        ],
        datasets: [
            {
                type: "line",
                label: "Profit",
                data: net,
                fill: false,
                borderColor: "rgba(237, 161, 13, 255)",
            },
            {
                type: "bar",
                label: "Revenue",
                data: revenues,
                backgroundColor: "rgba(195, 248, 223, 255)",
            },
            {
                type: "bar",
                label: "Expenses",
                data: expenses,
                backgroundColor: "rgba(199, 218, 255, 255)",
            },
        ],
    };
    const config = {
        type: "scatter",
        data: data,
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
        },
    };
    const receivablesCanvas = document.querySelector("#pl-canvas");
    if (myChart !== undefined) myChart.destroy();
    myChart = new Chart(receivablesCanvas, config);
}
