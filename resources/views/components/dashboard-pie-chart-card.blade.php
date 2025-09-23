@vite(['resources/css/components/dashboard/pie-chart-card.css'])
@props(['typeCount', 'clientTypes'])
<div class="chart-card grid-child-1">
    <div class="chart-header">
        <h3>Total Registered Clients</h3>
    </div>
    <div class="pie-chart">
        <canvas id="clients-chart"></canvas>
        @if ($typeCount < 1)
            <div class="no-tasks-container">
                <i class="fa-solid fa-ban"></i>
                <h1>No clients yet</h1>
                @if (in_array(request()->user()->role_id, [\App\Models\Role::ACCOUNTANT, \App\Models\Role::LIAISON]))
                    <form action="/clients">
                        <button type="submit">Add New Client</button>
                    </form>
                @else
                    <form action="/contact">
                        <button type="submit">Contact Accountant</button>
                    </form>
                @endif
            </div>
        @endif
    </div>
</div>

<script>
    const clientsChart = document.querySelector("canvas#clients-chart");
    const arr = @json($clientTypes);
    if (arr.length < 1) {
        clientsChart.style.display = "none";
        throw new Error("Insufficient data")
    };
    const data = {
        labels: Object.keys(arr),
        datasets: [{
            data: Object.values(arr),
            hoverOffset: 4,
        }, ],
    };

    const config = {
        type: "doughnut",
        data: data,
        options: {
            plugins: {
                legend: {
                    position: "bottom",
                },
            },
        },
    };

    new Chart(clientsChart, config);
</script>
