@props(['typeCount'])
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
