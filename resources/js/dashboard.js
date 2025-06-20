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

function barChart() {
    const labels = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
    ];
    const data = {
        labels: labels,
        datasets: [
            {
                label: "My First Dataset",
                data: [65, 59, 80, 81, 56, 55, 40],
                backgroundColor: [
                    "rgba(255, 99, 132, 0.2)",
                    "rgba(255, 159, 64, 0.2)",
                    "rgba(255, 205, 86, 0.2)",
                    "rgba(75, 192, 192, 0.2)",
                    "rgba(54, 162, 235, 0.2)",
                    "rgba(153, 102, 255, 0.2)",
                    "rgba(201, 203, 207, 0.2)",
                ],
                borderColor: [
                    "rgb(255, 99, 132)",
                    "rgb(255, 159, 64)",
                    "rgb(255, 205, 86)",
                    "rgb(75, 192, 192)",
                    "rgb(54, 162, 235)",
                    "rgb(153, 102, 255)",
                    "rgb(201, 203, 207)",
                ],
                borderWidth: 1,
            },
        ],
    };

    const config = {
        type: "bar",
        data: data,
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
