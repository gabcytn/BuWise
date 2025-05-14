  
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        @vite('resources/css/user-management/dashboard.css')
        <link rel="stylesheet" href="{{ asset('css/user-management/dashboard.css') }}">
    </head>
    <x-app-layout>

    <body>
        <div class="dashboard-wrapper">
            <!-- Profile Image & Greeting -->
            <div class="profile-section">
                <label for="profile-img-input" class="profile-img-wrapper">
                    <img id="profile-img" src="{{ asset('images/lallaine.png') }}" alt="Profile Image">
                </label>
                <div class="profile-info">
                    <h2 class="dashboard-title">
                        <span style="color: #1B80C3;">Hi!</span> {{ Auth::user()->name }}
                    </h2>
                    <p class="dashboard-email">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <hr class="section-divider">


            <!-- Quick Select Section -->
             <p class="quick-select-label"><strong>Quick select</strong></p>
            <div class="quick-select">
                <a href="{{ route('invoices.index') }}" class="dashboard-btn">
                    <span><img src="{{ asset('images/invoice1.png') }}" alt="Add Invoice"> Add Invoice</span>
                </a>
                <a href="{{ route('journal-entries.index') }}" class="dashboard-btn">
                    <span><img src="{{ asset('images/journal1.png') }}" alt="Add Journal Entry"> Add Journal Entry</span>
                </a>
                <a href="{{ route('ledger.coa') }}" class="dashboard-btn">
                    <span><img src="{{ asset('images/ledger1.png') }}" alt="View General Ledger"> View General Ledger</span>
                </a>
                <a href="#" class="dashboard-btn">
                    <span><img src="{{ asset('images/calendar1.png') }}" alt="View Calendar"> View Calendar</span>
                </a>
                <a href="{{ route('clients.index') }}" class="dashboard-btn">
                    <span><img src="{{ asset('images/users1.png') }}" alt="Manage Users"> Manage Users</span>
                </a>
            </div>

            <!-- Stats Section -->
            <div class="stats-section">
                <!-- Card 1: Total Clients for LBJ -->
                <div class="stat-card">
                    <h4 class="card-title">Total Clients for LBJ</h4>
                    <div class="stat-content">
                        <div class="client-count">
                            <div class="number">60</div>
                            <div class="label">Clients</div>
                        </div>
                        <div class="chart-wrap">
                            <canvas id="clientsChart" width="120" height="120"></canvas>
                        </div>
                    </div>
                    <div class="client-legend">
                        <div><span class="dot" style="background:#9E9EAF;"></span> Hotel</div>
                        <div><span class="dot" style="background:#3C91E6;"></span> Online Reseller</div>
                        <div><span class="dot" style="background:#F4B942;"></span> Freelancer</div>
                        <div><span class="dot" style="background:#F25F5C;"></span> Service-Based</div>
                    </div>
                </div>

                <!-- Card 2: User Management -->
                <div class="stat-card">
                    <h4>User Management</h4>
                    <div class="user-stats">
                        <div class="user-stat">
                            <div class="icon">
                                <img src="images/active-clients.png" alt="Active Clients" />
                            </div>
                            <div>
                                <strong class="green">60</strong><br />
                                <small>Active Clients</small>
                            </div>
                        </div>
                        <div class="user-stat">
                            <div class="icon">
                                <img src="images/active-users.png" alt="Active Staff" />
                            </div>
                            <div>
                                <strong class="purple">3</strong><br />
                                <small>Active Staff</small>
                            </div>
                        </div>
                        <div class="user-stat">
                            <div class="icon">
                                <img src="images/active-staff.png" alt="Active Users" />
                            </div>
                            <div>
                                <strong class="yellow">63</strong><br />
                                <small>Active Users</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Today's Tasks -->
                <!-- Card 3: Today's Tasks -->
<div class="stat-card">
    <h4>Today's Tasks</h4>
    <p style="color: gray; margin-top: -0.5rem; font-size: 14px;">To Dos</p>
    <ul class="task-list">
        <li>Sales Journal</li>
        <li>Invoice #1</li>
        <li>Invoice #2</li>
    </ul>
</div>
            </div>

        <!-- Chart Section -->
<div class="chart-section">
    <div class="dropdown-container">
        <label for="year-dropdown">Select Year:</label>
        <select id="year-dropdown" class="year-dropdown">
            <option value="2025">2025</option>
            <option value="2024">2024</option>
            <option value="2023">2023</option>
            <option value="2022">2022</option>
            <!-- Add more years as needed -->
        </select>
    </div>
    <h3>Tasks Completed per Month</h3>
    <canvas id="tasksChart" width="400" height="150"></canvas>
</div>


        <!-- Chart.js Library -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Preview Profile Image -->
        <script>
            function previewProfileImage(event) {
                const input = event.target;
                const img = document.getElementById('profile-img');
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>

        <!-- Charts -->
<script>
    window.onload = function () {
        const clientsCtx = document.getElementById('clientsChart');
        new Chart(clientsCtx, {
            type: 'pie', // changed from 'doughnut' to 'pie'
            data: {
                labels: ['Hotel', 'Freelancer', 'Online Reseller', 'Service-Based'],
                datasets: [{
                    data: [40, 10, 25, 25],
                    backgroundColor: ['#9E9EAF', '#F4B942', '#3C91E6', '#F25F5C'],
                    borderWidth: 2,
                    borderColor: '#fff',
                }]
            },
            options: {
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (context) => `${context.label}: ${context.parsed}%`
                        }
                    }
                }
            }
        });

                const tasksCtx = document.getElementById('tasksChart').getContext('2d');
                new Chart(tasksCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                        datasets: [{
                            label: 'Tasks Completed',
                            data: [20, 35, 40, 60, 30, 50, 70],
                            borderColor: '#007bff',
                            backgroundColor: 'rgba(0, 123, 255, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#007bff'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100
                            }
                        }
                    }
                });
            };
        </script>
    </body>
    </html>
</x-app-layout>
