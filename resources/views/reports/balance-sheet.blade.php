@php
    function getAbsoluteDifference($account)
    {
        return number_format(abs($account->debit - $account->credit), 2);
    }
@endphp
<x-app-layout title="Reports">
    @vite(['resources/css/reports/income-statement.css', 'resources/js/reports/balance-sheet.js'])
    <div class="container">
        <h2 id="page-title">Balance Sheet</h2>
        <p id="page-subtitle">Generate reports to examine your client's financial standing.</p>
        <form action="" method="GET">
            <div class="p-3 report-header">
                <div class="report-header__left">
                    <select class="select" name="period">
                        <option value="this_year" {{ request()->query('period') === 'this_year' ? 'selected' : '' }}>
                            This Year</option>
                        <option value="this_quarter"
                            {{ request()->query('period') === 'this_quarter' ? 'selected' : '' }}>
                            This Quarter</option>
                        <option value="this_month" {{ request()->query('period') === 'this_month' ? 'selected' : '' }}>
                            This Month</option>
                        <option value="this_week" {{ request()->query('period') === 'this_week' ? 'selected' : '' }}>
                            This Week</option>
                        <option value="today" {{ request()->query('period') === 'today' ? 'selected' : '' }}>
                            Today</option>
                        <option value="last_week" {{ request()->query('period') === 'last_week' ? 'selected' : '' }}>
                            Last Week</option>
                        <option value="last_month" {{ request()->query('period') === 'last_month' ? 'selected' : '' }}>
                            Last Month</option>
                        <option value="last_quarter"
                            {{ request()->query('period') === 'last_quarter' ? 'selected' : '' }}>
                            Last Quarter</option>
                        <option value="all_time" {{ request()->query('period') === 'all_time' ? 'selected' : '' }}>
                            All Time</option>
                    </select>
                    <select required name="client">
                        <option value="" {{ request()->query('client') ? '' : 'selected' }} disabled>Select Client
                        </option>
                        @foreach ($clients as $client)
                            <option {{ request()->query('client') === $client->id ? 'selected' : '' }}
                                value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit">Run Report</button>
                </div>
                <div class="report-header__right">
                    @if ($has_data)
                        <button type="button" id="download-table-btn">Export to CSV</button>
                    @endif
                </div>
            </div>
            <hr />
            <div class="p-3">
                @if ($has_data)
                    <div class="report-body">
                        <div class="report-body__header">
                            <p>{{ $selected_client->name }}</p>
                            <p><strong>Balance Sheet</strong></p>
                            <p>From {{ $start_date }} to {{ $end_date }}</p>
                        </div>
                        <div class="report-body__table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ACCOUNT</th>
                                        <th>TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Assets</strong></td>
                                        <td></td>
                                    </tr>
                                    @foreach ($assets as $asset)
                                        @php
                                            $value = getAbsoluteDifference($asset);
                                        @endphp
                                        <tr class="clickable"
                                            data-redirect="{{ route('ledger.coa.show', [$asset->acc_id, $selected_client]) }}">
                                            <td class="account-name">{{ $asset->acc_name }}</td>
                                            <td class="assets">
                                                {{ $asset->debit > $asset->credit ? $value : '-' . $value }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="total-row">
                                        <td><strong>Total Assets</strong></td>
                                        <td class="assets-total"><strong>0.00</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Liabilities</strong></td>
                                        <td></td>
                                    </tr>
                                    @foreach ($liabilities as $liability)
                                        @php
                                            $value = getAbsoluteDifference($liability);
                                        @endphp
                                        <tr class="clickable"
                                            data-redirect="{{ route('ledger.coa.show', [$liability->acc_id, $selected_client]) }}">
                                            <td class="account-name">{{ $liability->acc_name }}</td>
                                            <td class="liabilities">
                                                {{ $liability->debit > $liability->credit ? '-' . $value : $value }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="total-row">
                                        <td>Total Liabilities</td>
                                        <td class="liabilities-total">0.00</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Equities</strong></td>
                                        <td></td>
                                    </tr>
                                    @foreach ($equities as $equity)
                                        @php
                                            $value = getAbsoluteDifference($equity);
                                        @endphp
                                        <tr class="clickable"
                                            data-redirect="{{ route('ledger.coa.show', [$equity->acc_id, $selected_client]) }}">
                                            <td class="account-name">{{ $equity->acc_name }}</td>
                                            <td class="equities">
                                                {{ $equity->debit > $equity->credit ? '-' . $value : $value }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="clickable"
                                        data-redirect="{{ route('reports.income-statement', ['period' => request()->query('period'), 'client' => $selected_client->id]) }}">
                                        <td class="account-name">Current Period's Earnings</td>
                                        <td class="equities">{{ number_format($equity_from_income_statement, 2) }}</td>
                                    </tr>
                                    <tr class="total-row">
                                        <td>Total Equities</td>
                                        <td class="equities-total">0.00</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="total-row">
                                        <td>
                                            <strong>Total Liabilities and Equities</strong>
                                        </td>
                                        <td><strong id="net"></strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </form>
    </div>
</x-app-layout>
