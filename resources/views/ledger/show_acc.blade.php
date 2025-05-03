@php
    $headers = ['Date', 'Description', 'Transaction Type', 'Account Name', 'Account Group', 'Debit', 'Credit'];
@endphp
@vite(['resources/css/ledger/showAcc.css', 'resources/js/ledger/showAcc.js'])
<x-app-layout>
    <div class="container">
        <div class="title-row">
            <h1>{{ $user->name }}'s {{ $account->name }}</h1>
            <button id="set-initial-balance-btn">Set Initial Balance</button>
        </div>
        <x-table-management :headers=$headers>
            <tr>
                <td></td>
                <td>Opening balance</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ $entry_type === 'debit' ? number_format($initial_balance, 2) : '' }}</td>
                <td>{{ $entry_type === 'credit' ? number_format($initial_balance, 2) : '' }}</td>
            </tr>
            @foreach ($data as $datum)
                <tr>
                    <td>{{ formatDate($datum->journal_date) }}</td>
                    <td>{{ truncate($datum->journal_description) }}</td>
                    <td>{{ $datum->transaction_type }}</td>
                    <td>{{ $datum->acc_name }}</td>
                    <td>{{ ucfirst($datum->acc_group) }}</td>
                    <td>{{ $datum->debit ? number_format($datum->debit, 2) : '' }}</td>
                    <td>{{ $datum->credit ? number_format($datum->credit, 2) : '' }}</td>
                </tr>
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
        <form action="{{ route('ledger.coa') }}">
            <button type="submit">Back</button>
        </form>
    </div>
    <dialog id="set-initial-balance-dialog">
        <form action="{{ route('ledger.coa.update_initial', [$account, $user]) }}" method="POST">
            @csrf
            <label for="initial-balance">Current initial balance</label>
            <div class="input-wrapper">
                <input value="{{ $initial_balance }}" name="initial_balance" id="initial-balance" />
                <div class="select-wrapper">
                    <select required id="entry-type-select" name="entry_type_id">
                        <option value="" selected disabled>Select an entry type</option>
                        <option value="{{ \App\Models\EntryType::DEBIT }}">Debit</option>
                        <option value="{{ \App\Models\EntryType::CREDIT }}">Credit</option>
                    </select>
                </div>
            </div>
            <p id="note-message">NOTE: this will set the VERY initial balance of this account and will affect the
                reports.</p>
            <button type="submit">Submit anyway</button>
            <button type="button">Cancel</button>
        </form>
    </dialog>
</x-app-layout>
