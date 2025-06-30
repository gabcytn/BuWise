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
    if (roles.length < 1 || data.months.length < 1) {
        noDataForLineChart();
        return;
    }
    const dataset = {
        labels: data.months,
        datasets: Object.values(data)
            .filter((item) => typeof item[0] === "number")
            .map((item, idx) => {
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
    if (data.labels.length < 1 && data.values.length < 1) {
        noDataForBarChart();
        return;
    }
    const dataset = {
        labels: data.labels,
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
            plugins: {
                legend: {
                    display: false,
                },
            },
        },
    };

    new Chart(journalsChart, config);
}

function noDataForLineChart() {
    const lineChartContainer = document.querySelector(".line-chart");
    const div = document.createElement("div");
    div.classList.add("no-tasks-container");
    const icon = document.createElement("i");
    icon.className = "fa-solid fa-ban";
    const h1 = document.createElement("h1");
    h1.textContent = "No tasks completed yet.";
    const form = document.createElement("form");
    form.action = window.location.origin + "/tasks/todo";
    const button = document.createElement("button");
    button.type = "submit";
    button.textContent = "Finish Tasks";

    form.appendChild(button);
    div.appendChild(icon);
    div.appendChild(h1);
    div.appendChild(form);

    tasksChart.style.display = "none";
    lineChartContainer.parentElement.style.display = "grid";
    lineChartContainer.parentElement.style.justifyContent = "center";
    lineChartContainer.appendChild(div);
}

function noDataForBarChart() {
    const barChartContainer = document.querySelector(".bar-chart");
    const div = document.createElement("div");
    div.classList.add("no-tasks-container");
    const icon = document.createElement("i");
    icon.className = "fa-solid fa-ban";
    const h1 = document.createElement("h1");
    h1.textContent = "No journals yet";
    const form = document.createElement("form");
    form.action = window.location.origin + "/journal-entries";
    const button = document.createElement("button");
    button.type = "submit";
    button.textContent = "Create Journals";

    form.appendChild(button);
    div.appendChild(icon);
    div.appendChild(h1);
    div.appendChild(form);

    journalsChart.style.display = "none";
    barChartContainer.appendChild(div);
}
