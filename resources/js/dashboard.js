const tasksChart = document.querySelector("canvas#tasks-chart");
const journalsChart = document.querySelector("canvas#journals-chart");

lineChart();
barChart();

async function lineChart() {
    const res = await fetch("/dashboard/charts/tasks", {
        headers: {
            Accept: "application/json",
        },
    });
    const data = await res.json();
    const roles = Object.keys(data).filter((item) => item !== "months");
    const dataset = {
        labels: data.months,
        datasets: Object.values(data).map((item, idx) => {
            return {
                label: roles[idx],
                data: item,
            };
        }),
    };

    const config = {
        type: "line",
        data: dataset,
        options: {
            responsive: true,
            stacked: false,
            plugins: {
                legend: {
                    position: "bottom",
                },
            },
        },
    };

    new Chart(tasksChart, config);
}

async function barChart() {
    const res = await fetch("/dashboard/charts/journals");
    const data = await res.json();
    console.log(data);
    const labels = data.labels;
    const dataset = {
        labels: labels,
        datasets: [
            {
                data: data.values,
            },
        ],
    };

    const config = {
        type: "bar",
        data: dataset,
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
        },
    };

    new Chart(journalsChart, config);
}
