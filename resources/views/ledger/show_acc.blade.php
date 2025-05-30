@php
    $headers = ['Date', 'Description', 'Transaction Type', 'Account Name', 'Account Group', 'Debit', 'Credit'];
@endphp
@vite(['resources/css/ledger/showAcc.css', 'resources/js/ledger/showAcc.js'])
<x-app-layout>
    <div class="container">
        <div class="title-row">
            <h1>{{ $user->name }}'s {{ $account->name }}</h1>
        </div>
        <select required name="date_range" id="date-range-select">
            @if (request()->query('start') && request()->query('end'))
                <option selected value="custom" id="custom-option">Custom</option>
                <option value="all_time" id="alltime-option">All time</option>
            @else
                <option selected value="all_time" id="alltime-option">All time</option>
                <option id="custom-option" value="custom">Custom</option>
            @endif
        </select>
        <x-table-management :headers=$headers>
            <tr>
                <td></td>
                <td>Opening balance</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ $opening_entry_type === \App\Models\EntryType::DEBIT ? number_format($opening_balance, 2) : '' }}
                </td>
                <td>{{ $opening_entry_type === \App\Models\EntryType::CREDIT ? number_format($opening_balance, 2) : '' }}
                </td>
            </tr>
            @foreach ($data as $datum)
                @if ($datum->transaction_date >= $start && $datum->transaction_date <= $end)
                    <tr>
                        <td>{{ formatDate($datum->transaction_date) }}</td>
                        <td>{{ truncate($datum->transaction_description) }}</td>
                        <td>{{ $datum->transaction_type }}</td>
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
        @if ($errors->any())
            <p style="color: red; font-size: 0.85rem;">{{ $errors->first() }}</p>
        @endif
        <button type="button" id="back-button">Back</button>
    </div>
    <dialog id="set-custom-date-range-dialog">
        <h2>Choose starting and ending date</h2>
        <form id="date-range-form">
            <div class="date-input-wrapper">
                <input type="date" name="start" id="start" required />
                <input type="date" name="end" id="end" required />
            </div>
            <div class="submit-btn-wrapper">
                <button type="submit">Run</button>
                <button type="button">Cancel</button>
            </div>
        </form>
    </dialog>
</x-app-layout>
