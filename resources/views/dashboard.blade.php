<x-app-layout title="Dashboard">
    @vite(['resources/css/user-management/dashboard.css', 'resources/js/dashboard.js'])
    @php
        $user = request()->user();
    @endphp

    <div class="container">
        <x-dashboard-greeting />

        <section class="cards-row">
            <x-dashboard-numeric-card icon="fa-user" title="Total Active Clients" count="{{ $clients_count }}" />
            <x-dashboard-numeric-card icon="fa-file-lines" title="Invoices Uploaded" count="{{ $invoices_count }}" />
            <x-dashboard-numeric-card icon="fa-newspaper" title="Total Entries" count="{{ $journals_count }}" />
            <x-dashboard-numeric-card icon="fa-address-card" title="Total Active Staff" count="{{ $staff_count }}" />
        </section>

        <section class="charts-section">
            <x-dashboard-pie-chart-card typeCount="{{ count($client_types) }}" />
            <x-dashboard-line-chart />
            <x-dashboard-todo-list :tasks="$tasks" />
            <x-dashboard-bar-chart />
        </section>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const clientsChart = document.querySelector("canvas#clients-chart");
            const arr = @json($client_types);
            console.log(arr);
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

</x-app-layout>
