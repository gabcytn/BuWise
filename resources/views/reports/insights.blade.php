@vite(['resources/css/reports/insights.css', 'resources/js/reports/insights.js'])
<x-app-layout>
    <div class="container">
        <div class="insights-header">
            <div class="insights-header__left">
                <h2 id="page-title">Client Overview</h2>
                <p>{{ \Carbon\Carbon::now()->format('Y M d') }} | {{ request()->user()->role->name }}</p>
            </div>
            <form class="insights-header__right">
                <select name="client" required>
                    <option value="" selected disabled>Select Client</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
                <button type="submit">Run Reports</button>
            </form>
        </div>
        <div class="insights-body d-none">
            <div class="receivables-vs-payables">
                <div class="receivables">
                </div>
                <div class="payables">
                </div>
            </div>
            <div class="profit-and-loss">
            </div>
            <div class="cash-flow">
                <div class="cash-flow__header">
                    <h3>Cash Flow</h3>
                    <select name="cash-flow-period">
                        @foreach ($periods as $period)
                            <option value="{{ $period }}">{{ $period }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="cash-flow__body">
                    <div class="chart-container">
                        <canvas id="cash-flow__body--canvas"></canvas>
                    </div>
                    <div class="cash-flow__body--texts">
                        <div class="inflows summary-card">
                            <h6 class="card-label">Inflows</h6>
                            <p class="card-value">10,007.99</p>
                        </div>
                        <div class="outflows summary-card">
                            <h6 class="card-label">Outflows</h6>
                            <p class="card-value">8971.05</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</x-app-layout>
