@props(['values'])
<div class="chart-card grid-child-2">
    <div class="chart-header">
        <h3>Total Tasks Completed per User</h3>
    </div>
    <div class="line-chart">
        <canvas id="tasks-chart"></canvas>
    </div>
</div>

<script>
    // "values" variable data structure
    // {
    //     months: ["Apr", "Jul", "Sep"]
    //     Accountant: [1, 2, 2],
    //     Liaison: [0, 5, 2],
    // }
    const tasksChart = document.querySelector("canvas#tasks-chart");
    lineChart()

    function lineChart() {
        const data = @json($values);
        const roles = Object.keys(data).filter((item) => item !== "months");
        if (roles.length < 1 || data.months.length < 1) {
            noDataForLineChart();
            return;
        }
        const datasetValues = Object.values(data)
            .filter((item) => typeof item[0] === "number")
            .map((item, idx) => {
                return {
                    label: roles[idx],
                    data: item,
                };
            });

        const dataset = {
            labels: data.months,
            datasets: datasetValues,
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
</script>
