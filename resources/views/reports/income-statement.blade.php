@php
    function getAbsoluteDifference($account)
    {
        return number_format(abs($account->debit - $account->credit), 2);
    }
@endphp
<x-app-layout title="Reports">
    @vite(['resources/css/reports/income-statement.css', 'resources/js/reports/income-statement.js'])

    <div class="container">
        <h2 id="page-title">Income Statement</h2>
        <p id="page-subtitle">Generate reports to examine your client's financial standing.</p>

        <form action="" method="GET">
            <div class="p-3 report-header">
                <div class="report-header__left">
                    <select class="select" name="period">
                        <option value="this_year" {{ request()->query('period') === 'this_year' ? 'selected' : '' }}>
                            This
                            Year</option>
                        <option value="this_quarter"
                            {{ request()->query('period') === 'this_quarter' ? 'selected' : '' }}>
                            This Quarter</option>
                        <option value="this_month" {{ request()->query('period') === 'this_month' ? 'selected' : '' }}>
                            This Month</option>
                        <option value="this_week" {{ request()->query('period') === 'this_week' ? 'selected' : '' }}>
                            This Week</option>
                        <option value="today" {{ request()->query('period') === 'today' ? 'selected' : '' }}>Today
                        </option>
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
                        <div class="dropdown">
                            <button type="button" class="dropdown-toggle">Download File ▾</button>
                            <ul class="dropdown-menu">
                                <li><a href="#" id="download-table-btn">✅ Download as CSV</a></li>
                                <li><span class="disabled-item">🚫 Download as PDF</span></li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <hr />

            <div class="p-3">
                @if ($has_data)
                    <div class="report-body">
                        <div class="report-body__header">
                            <p>{{ $selected_client->name }}</p>
                            <p><strong>Income Statement</strong></p>
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
                                        <td><strong>Revenues</strong></td>
                                        <td></td>
                                    </tr>
                                    @foreach ($revenues as $revenue)
                                        @php
                                            $value = getAbsoluteDifference($revenue);
                                        @endphp
                                        <tr class="clickable"
                                            data-redirect="{{ route('ledger.coa.show', [$revenue->acc_id, $selected_client]) }}">
                                            <td class="account-name">{{ $revenue->acc_name }}</td>
                                            <td class="revenues">
                                                {{ $revenue->debit > $revenue->credit ? '-' . $value : $value }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="total-row">
                                        <td>Total Revenues</td>
                                        <td class="revenues-total">0.00</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Expenses</strong></td>
                                        <td></td>
                                    </tr>
                                    @foreach ($expenses as $expense)
                                        @php
                                            $value = getAbsoluteDifference($expense);
                                        @endphp
                                        <tr class="clickable"
                                            data-redirect="{{ route('ledger.coa.show', [$expense->acc_id, $selected_client]) }}">
                                            <td class="account-name">{{ $expense->acc_name }}</td>
                                            <td class="expenses">
                                                {{ $expense->debit > $expense->credit ? $value : '-' . $value }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="total-row">
                                        <td>Total Expenses</td>
                                        <td class="expenses-total">0.00</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="total-row">
                                        <td><strong>Net Profit/Loss</strong></td>
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
