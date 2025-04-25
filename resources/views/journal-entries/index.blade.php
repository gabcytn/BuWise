<x-app-layout>
    @if (session('status'))
        <p style="color: red;">{{ session('status') }}</p>
    @endif
    @vite(['resources/css/journal-entries/create.css', 'resources/js/journal-entries/create.js'])
    <h2>Journal Entry</h2>
    <datalist id="accounts-list">
        @foreach ($accounts as $account)
            <option value="{{ $account->id . ' ' . $account->name }}" />
        @endforeach
    </datalist>
    <datalist id="transaction-types">
        @foreach ($transactionTypes as $transactionType)
            <option value="{{ $transactionType->name }}" />
        @endforeach
    </datalist>
    <form id="journalForm" method="POST" action="{{ route('journal-entries.store') }}">
        @csrf
        <table id="journalTable">
            <thead>
                <tr>
                    <th>ACCOUNT</th>
                    <th>TRANSACTION TYPE</th>
                    <th>DEBITS</th>
                    <th>CREDITS</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="journalBody">
                <!-- Rows will be added here -->
            </tbody>
            <tfoot>
                <tr class="totals-row">
                    <td colspan="2" style="text-align: right;">Totals:</td>
                    <td id="totalDebits">0.00</td>
                    <td id="totalCredits">0.00</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <button type="button" class="add-row-btn">
            Add New Row
        </button>

        <div>
            <button type="submit" class="submit-btn" id="submitButton" disabled>Submit Journal Entry</button>
            <span id="balanceWarning" style="color: red; margin-left: 10px; display: none;">
                Debits and credits must be equal before submitting
            </span>
        </div>
    </form>
</x-app-layout>

<script>
    document.getElementById('date').valueAsDate = new Date();
</script>
