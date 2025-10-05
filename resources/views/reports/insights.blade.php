@vite(['resources/css/reports/insights.css', 'resources/js/reports/insights.js'])
<x-app-layout title="Insights">
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
                    <div class="p-3">
                        <div class="pr-texts">
                            <h2>Total Receivables</h2>
                        </div>
                        <div class="chart-container">
                            <canvas id="receivables-canvas"></canvas>
                        </div>
                    </div>
                    <hr />
                    <div class="p-3">
                        <div class="receivables-summary pr-summary">

                        </div>
                    </div>
                </div>
                <div class="payables">
                    <div class="p-3">
                        <div class="pr-texts">
                            <h2>Total Payables</h2>
                        </div>
                        <div class="chart-container">
                            <canvas id="payables-canvas"></canvas>
                        </div>
                    </div>
                    <hr />
                    <div class="p-3">
                        <div class="payables-summary pr-summary">

                        </div>
                    </div>
                </div>
            </div>
            <div class="barchart-card">
                <div class="barchart-card__header">
                    <h2>Profit and Loss</h2>
                </div>
                <div class="barchart-card__body">
                    <div class="chart-container">
                        <canvas id="pl-canvas"></canvas>
                    </div>
                    <div class="barchart-card__body--texts">
                        <div class="summary-card pl-revenue">
                            <h6 class="card-label">Total Revenue</h6>
                            <p class="card-value"></p>
                        </div>
                        <div class="summary-card pl-expenses">
                            <h6 class="card-label">Total Expenses</h6>
                            <p class="card-value"></p>
                        </div>
                        <div class="summary-card pl-profit">
                            <h6 class="card-label">Profit</h6>
                            <p class="card-value"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="barchart-card">
                <div class="barchart-card__header">
                    <h3>Cash Flow</h3>
                </div>
                <div class="barchart-card__body">
                    <div class="chart-container">
                        <canvas id="cash-flow__body--canvas"></canvas>
                    </div>
                    <div class="barchart-card__body--texts">
                        <div class="inflows summary-card">
                            <h6 class="card-label">Inflows</h6>
                            <p class="card-value"></p>
                        </div>
                        <div class="outflows summary-card">
                            <h6 class="card-label">Outflows</h6>
                            <p class="card-value"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</x-app-layout>
