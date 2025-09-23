@props(['values'])
<div class="chart-card grid-child-4">
    <div class="chart-header">
        <h3>Total Journal Entries Published</h3>
    </div>
    <div class="bar-chart">
        <canvas id="journals-chart"></canvas>
    </div>
</div>

<script>
    const months = [
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

    const journalsChart = document.querySelector("canvas#journals-chart");
    barChart();

    function barChart() {
        const data = @json($values);
        if (data.length < 1) {
            noDataForBarChart();
            return;
        }
        const dataset = {
            labels: months,
            datasets: [{
                data: data,
            }, ],
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
</script>
