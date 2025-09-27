@php
    $headers = ['Date', 'Description', 'Transaction Type', 'Account Name', 'Account Group', 'Debit', 'Credit'];
    $options = ['This year', 'This month', 'This week', 'Last week', 'Last month', 'Last year', 'All time'];
@endphp

@vite(['resources/css/ledger/showAcc.css'])

<x-app-layout title="Ledger">
    <div class="container">
        <div class="title-row">
            <h1>{{ $user->name }}'s {{ $account->name }}</h1>
        </div>


        <form action="{{ route('ledger.coa.show', [$account, $user]) }}" method="GET" class="filter-row">
            <x-dropdown label="Period" :options="$options" name="period" />
            <button type="submit">Run</button>
        </form>

        <x-table-management :headers=$headers>
            @php
                $firstRow = 'Opening balance (excluded in total)';
            @endphp
            <tr>
                <td></td>
                <td title="{{ $firstRow }}">{{ $firstRow }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ $opening_entry_type === 'debit' ? number_format($opening_balance, 2) : '' }}</td>
                <td>{{ $opening_entry_type === 'credit' ? number_format($opening_balance, 2) : '' }}</td>
            </tr>
            @foreach ($data as $datum)
                @if ($datum->transaction_date >= $start && $datum->transaction_date <= $end)
                    <tr>
                        <td>{{ formatDate($datum->transaction_date) }}</td>
                        <td title="{{ $datum->transaction_description }}">
                            {{ truncate($datum->transaction_description) }}</td>
                        <td>{{ ucfirst($datum->transaction_type) }}</td>
                        <td>{{ $datum->acc_name }}</td>
                        <td>{{ ucfirst($datum->acc_group) }}</td>
                        <td>{{ $datum->debit ? number_format($datum->debit, 2) : '' }}</td>
                        <td>{{ $datum->credit ? number_format($datum->credit, 2) : '' }}</td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <td></td>
                <td><strong>TOTAL</strong></td>
                <td></td>
                <td></td>
                <td></td>
                <td><strong>{{ number_format($total_debits, 2) }}</strong></td>
                <td><strong>{{ number_format($total_credits, 2) }}</strong></td>
            </tr>
            <tr class="bg-off-white">
                <td></td>
                <td><strong>Closing balance</strong></td>
                <td></td>
                <td></td>
                <td></td>
                <td><strong>{{ $total_debits > $total_credits ? $overall : '' }}</strong></td>
                <td><strong>{{ $total_credits >= $total_debits ? $overall : '' }}</strong></td>
            </tr>
        </x-table-management>

        <button type="button" id="back-button">Go Back</button>
    </div>
    <script>
        document.querySelector("#back-button").addEventListener("click", () => {
            window.history.back();
        });
    </script>

</x-app-layout>
