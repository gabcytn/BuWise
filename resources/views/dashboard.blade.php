<x-app-layout title="Dashboard">
    @vite(['resources/css/user-management/dashboard.css', 'resources/js/dashboard.js'])

    <div class="container">
        <!-- Profile Image & Greeting -->
        <div class="profile-section">
            <div class="profile-img-wrapper">
                <img id="profile-img" src="{{ asset('storage/profiles/' . Auth::user()->profile_img) }}"
                    alt="Profile Image" />
            </div>
            <div class="profile-info">
                <h2 class="dashboard-title">
                    Welcome, {{ Auth::user()->name }}!
                </h2>
                <p class="dashboard-role">{{ $role }} of {{ $organization }}</p>
            </div>
        </div>

        <!-- Stats Section -->
        <section class="cards-row">
            <!-- Card 1 -->
            <div class="card">
                <div class="icon-container">
                    <i class="fa-regular fa-user"></i>
                </div>
                <div class="card-details">
                    <h3>Total Active Clients</h3>
                    <p>{{ $clients_count }}</p>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="card">
                <div class="icon-container">
                    <i class="fa-regular fa-file-lines"></i>
                </div>
                <div class="card-details">
                    <h3>Invoices Uploaded</h3>
                    <p>{{ $invoices_count }}</p>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="card">
                <div class="icon-container">
                    <i class="fa-regular fa-newspaper"></i>
                </div>
                <div class="card-details">
                    <h3>Total Entries</h3>
                    <p>{{ $journals_count }}</p>
                </div>
            </div>
            <!-- Card 4 -->
            <div class="card">
                <div class="icon-container">
                    <i class="fa-regular fa-address-card"></i>
                </div>
                <div class="card-details">
                    <h3>Total Active Staff</h3>
                    <p>{{ $staff_count }}</p>
                </div>
            </div>
        </section>

        <section class="charts-section">
            <!-- doughnut chart -->
            <div class="chart-card grid-child-1">
                <div class="chart-header">
                    <h3>Total Registered Clients</h3>
                </div>
                <div class="pie-chart">
                    <canvas id="clients-chart"></canvas>
                    @if (count($client_types) < 1)
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

            <!-- line chart -->
            <div class="chart-card grid-child-2">
                <div class="chart-header">
                    <h3>Total Tasks Completed per User</h3>
                </div>
                <div class="line-chart">
                    <canvas id="tasks-chart"></canvas>
                </div>
            </div>

            <!-- Todo list -->
            <div class="chart-card grid-child-3">
                <div class="tasks-header">
                    <h3>To Do List</h3>
                </div>
                @if (count($tasks) > 0)
                    <ul class="tasks-list">
                        @foreach ($tasks as $item)
                            <li class="task-item">
                                <div class="task-content">
                                    <div class="task-title">{{ $item->name }}</div>
                                    <div class="task-meta">Due:
                                        {{ \Carbon\Carbon::createFromDate($item->end_date)->format('M d Y') }}</div>
                                    <div class="task-assigned">Created by {{ $item->creator->name }}</div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="no-tasks-container">
                        <i class="fa-solid fa-ban"></i>
                        <h1>No tasks yet</h1>
                        @if (request()->user()->role_id === \App\Models\Role::ACCOUNTANT)
                            <form action="/tasks">
                                <button type="submit">Add New Task</button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
            <!-- Bar chart -->
            <div class="chart-card grid-child-4">
                <div class="chart-header">
                    <h3>Total Journal Entries Published</h3>
                </div>
                <div class="bar-chart">
                    <canvas id="journals-chart"></canvas>
                </div>
            </div>

        </section>

        <!-- Chart.js Library -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const clientsChart = document.querySelector("canvas#clients-chart");
            const arr = @json($client_types);
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
