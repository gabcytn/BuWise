export async function getReceivablesData(clientId) {
    const res = await fetch(`/receivables/${clientId}`);
    const data = await res.json();

    const entries = new Map();
    data.forEach((item) => {
        let v = entries.get(item.acc_name) ?? 0;
        switch (item.entry_type) {
            case "debit":
                v += item.amount;
                break;
            case "credit":
                v -= item.amount;
                break;
            default:
                break;
        }
        entries.set(item.acc_name, v);
    });

    const keys = Array.from(entries.keys());
    const toPass = [];
    keys.forEach((key) => {
        toPass.push({
            account: key,
            amount: [entries.get(key)],
        });
    });

    start(toPass);
}

function start(entries) {
    const config = {
        type: "pie",
        data: {
            labels: entries.map((entry) => entry.account),
            datasets: [
                {
                    data: entries.map((entry) => entry.amount),
                },
            ],
        },
        options: {
            plugins: {
                legend: {
                    display: false,
                },
            },
        },
    };
    const receivablesCanvas = document.querySelector("#receivables-canvas");
    const myChart = new Chart(receivablesCanvas, config);
    createSummary(entries);
}

function createSummary(entries) {
    const accountNames = entries.map((entry) => entry.account);
    const accountValues = entries.map((entry) => entry.amount);

    const summaryDiv = document.querySelector(".receivables-summary");

    for (let i = 0; i < accountNames.length; i++) {
        const div = document.createElement("div");
        const title = document.createElement("h3");
        const amount = document.createElement("p");

        title.textContent = accountNames[i];
        amount.textContent = formatNumber(accountValues[i], 2);

        div.appendChild(title);
        div.appendChild(amount);

        summaryDiv.appendChild(div);
    }
}

function formatNumber(number, decimals) {
    return Number(number).toLocaleString("en-US", {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    });
}
