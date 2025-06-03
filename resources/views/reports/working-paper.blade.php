<x-app-layout>
    @vite(['resources/css/reports/income-statement.css', 'resources/js/reports/working-paper.js'])
    <div class="container">
        <h2 id="page-title">Working Paper</h2>
        <p id="page-subtitle">Generate reports to examine your client's financial standing.</p>
        <form action="" method="GET">
            <div class="p-3 report-header">
                <div class="report-header__left">
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
                    <select name="account" required>
                        <option value="" {{ request()->query('account') ? '' : 'selected' }} disabled>Select an
                            Account</option>
                        @foreach ($accounts as $account)
                            <option {{ request()->query('account') === $account->id ? 'selected' : '' }}
                                value="{{ $account->id }}">{{ $account->code . ' ' . $account->name }}</option>
                        @endforeach
                    </select>
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
                            <p><strong>Working Paper</strong></p>
                        </div>
                        <div class="report-body__table">
                            <table id="table">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Account</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="clickable cursor-default">
                                        <td>Beginning Balance</td>
                                        <td></td>
                                        <td class="debit">{{ number_format($opening_debits, 2) }}</td>
                                        <td class="credit">{{ number_format($opening_credits, 2) }}</td>
                                        <td class="row-total">0.00</td>
                                    </tr>
                                    @foreach ($months as $key => $month)
                                        <tr class="clickable cursor-default">
                                            <td>{{ $month }}</td>
                                            <td>{{ $selected_account->name }}</td>
                                            <td class="debit">
                                                {{ array_key_exists($key, $data) ? number_format($data[$key]->debit, 2) : '0.00' }}
                                            <td class="credit">
                                                {{ array_key_exists($key, $data) ? number_format($data[$key]->credit, 2) : '0.00' }}
                                            </td>
                                            <td class="row-total">0.00</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="total-row">
                                        <td>
                                            <strong>Total {{ $selected_account->name }}</strong>
                                        </td>
                                        <td></td>
                                        <td><strong id="net-debit">0.00</strong></td>
                                        <td><strong id="net-credit">0.00</strong></td>
                                        <td><strong id="net">0.00</strong></td>
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
