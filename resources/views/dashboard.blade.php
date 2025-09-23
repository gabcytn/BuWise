<x-app-layout title="Dashboard">
    @vite(['resources/css/user-management/dashboard.css'])
    @php
        $user = request()->user();
    @endphp
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="container">
        <x-dashboard-greeting />

        <section class="cards-row">
            <x-dashboard-numeric-card icon="fa-user" title="Total Active Clients" count="{{ $clients_count }}" />
            <x-dashboard-numeric-card icon="fa-file-lines" title="Invoices Uploaded" count="{{ $invoices_count }}" />
            <x-dashboard-numeric-card icon="fa-newspaper" title="Total Entries" count="{{ $journals_count }}" />
            <x-dashboard-numeric-card icon="fa-address-card" title="Total Active Staff" count="{{ $staff_count }}" />
        </section>

        <section class="charts-section">
            <x-dashboard-pie-chart-card typeCount="{{ count($client_types) }}" :clientTypes="$client_types" />
            <x-dashboard-line-chart :values="$line_chart_data" />
            <x-dashboard-bar-chart :values="$bar_chart_data" />
            <x-dashboard-todo-list :tasks="$todo_list" />
        </section>

    </div>


</x-app-layout>
