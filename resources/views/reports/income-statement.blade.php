<x-app-layout>
    @vite(['resources/css/reports/income-statement.css', 'resources/js/reports/income-statement.js'])
    <div class="container">
        <h2 id="page-title">Income Statement</h2>
        <p id="page-subtitle">Generate reports to examine your client's financial standing.</p>
        <form action="" method="GET">
            <div class="p-3 report-header">
                <div class="report-header__left">
                    <select class="select" name="period">
                        <option value="this_year" {{ request()->query('period') === 'this_year' ? 'selected' : '' }}>
                            This Year</option>
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
                    <button type="button" id="download-table-btn">Export to CSV</button>
                </div>
            </div>
            <hr />
            <div class="p-3">
                @if (session('has_data'))
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
                                        <tr class="clickable"
                                            data-redirect="{{ route('ledger.coa.show', [$revenue->acc_id, $selected_client]) }}">
                                            <td class="account-name">{{ $revenue->acc_name }}</td>
                                            <td class="revenues">
                                                {{ $revenue->debit > 0 ? '-' . number_format($revenue->debit, 2) : number_format($revenue->credit, 2) }}
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
                                        <tr class="clickable"
                                            data-redirect="{{ route('ledger.coa.show', [$expense->acc_id, $selected_client]) }}">
                                            <td class="account-name">{{ $expense->acc_name }}</td>
                                            <td class="expenses">
                                                {{ $expense->debit > 0 ? number_format($expense->debit, 2) : '-' . number_format($expense->credit, 2) }}
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
                                        <td>
                                            <strong>Net Profit/Loss</strong>
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
