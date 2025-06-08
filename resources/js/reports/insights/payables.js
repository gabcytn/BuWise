export async function getPayablesData(clientId) {
    const res = await fetch(`/payables/${clientId}`);
    const data = await res.json();

    console.log(data);

    const entries = new Map();
    data.forEach((item) => {
        if (item.entry_type === "debit") return;
        let v;
        if (entries.get(item.acc_name)) {
            v = entries.get(item.acc_name);
            v += item.amount;
        } else {
            v = item.amount;
        }

        entries.set(item.acc_name, v);
    });

    const keys = Array.from(entries.keys());
    const toPass = [];
    keys.forEach((key, idx) => {
        toPass.push({
            account: keys[idx],
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
    };
    const receivablesCanvas = document.querySelector("#payables-canvas");
    const myChart = new Chart(receivablesCanvas, config);
}
