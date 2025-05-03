@php
    $headers = ['Date', 'Description', 'Transaction Type', 'Account Name', 'Account Group', 'Debit', 'Credit'];
@endphp
<x-app-layout>
    <div class="container" style="max-width: 1000px; width: 90%; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between;">
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
            <tr style="background-color: var(--off-white);">
                <td></td>
                <td><strong>Closing balance</strong></td>
                <td></td>
                <td></td>
                <td></td>
                <td><strong>{{ $total_debits > $total_credits ? $overall : '' }}</strong></td>
                <td><strong>{{ $total_credits > $total_debits ? $overall : '' }}</strong></td>
            </tr>
        </x-table-management>
        <form action="{{ url()->previous() }}">
            <button type="submit">Back</button>
        </form>
    </div>
    <dialog id="set-initial-balance-dialog">
        <form action="" method="POST" style="flex-direction: column; gap: 1rem;">
            @csrf
            <div class="input-box">
                <label style="font-size: 0.85rem;" for="initial-balance">Current initial balance</label>
                <input value="{{ $initial_balance }}" name="initial_balance" id="initial-balance" />
            </div>
            <p style="color: red; font-size: 0.85rem;">NOTE: this will set the VERY initial balance of this account and
                will affect the reports.</p>
            <button type="submit">Submit anyway</button>
            <button type="button">Cancel</button>
        </form>
    </dialog>
    <script>
        const btn = document.querySelector("#set-initial-balance-btn");
        const cancelBtn = document.querySelector("#set-initial-balance-dialog button[type='button']");
        const dialog = document.querySelector("#set-initial-balance-dialog");

        btn.addEventListener("click", () => {
            dialog.showModal();
        })

        cancelBtn.addEventListener("click", () => {
            dialog.close();
        })
    </script>
</x-app-layout>
