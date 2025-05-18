<x-app-layout>
    @if (session('status'))
        <p style="color: red;">{{ session('status') }}</p>
    @endif
    @vite(['resources/css/journal-entries/create.css', 'resources/js/journal-entries/create.js', 'resources/js/journal-entries/create-radio-buttons.js'])
    <div class="container">
        <div class="radio-buttons">
            <div class="radio-group">
                <label for="journal">Journal</label>
                <input type="radio" name="invoice-journal" id="journal" value="journal" />
            </div>
            <div class="radio-group">
                <label for="invoice">Invoice</label>
                <input type="radio" name="invoice-journal" id="invoice" value="invoice" />
            </div>
        </div>
        <h2 id="page-title">Journal Entry</h2>
        <select style="display: none;" id="select-account" required>
            <option value="" selected disabled>Select an account</option>
            @foreach ($accounts as $account)
                <option value="{{ $account->id }}">{{ $account->id . ' ' . $account->name }}</option>
            @endforeach
        </select>
        <form id="journalForm" method="POST" action="{{ route('journal-entries.store') }}">
            @csrf
            <div class="invoice-components">
                <div class="input-wrapper">
                    <label for="invoice-id">Invoice ID</label>
                    <input type="text" name="invoice_id" id="invoice-id" />
                </div>
            </div>
            <div class="row">
                <div class="input-wrapper">
                    <label for="date">Date</label>
                    <input type="date" name="date" id="date" value="{{ old('date') }}" />
                </div>
                <div class="input-wrapper">
                    <label for="transaction-type">Transaction Type</label>
                    <select required id="transaction-type" name="transaction_type_id">
                        <option value="" selected disabled>Select a transaction type</option>
                        @foreach ($transactionTypes as $transactionType)
                            <option value="{{ $transactionType->id }}">{{ $transactionType->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-wrapper">
                    <label for="client-select">Client</label>
                    <select name="client_id" id="client-select" required="">
                        <option value="" disabled selected>Select a client</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="input-wrapper">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
            </div>
            <div class="table-wrapper">
                <table id="journalTable">
                    <thead>
                        <tr>
                            <th>ACCOUNT</th>
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
                            <td></td>
                            <td id="totalDebits">
                                <div style="margin-left: 0.5rem;">0.00</div>
                            </td>
                            <td id="totalCredits">
                                <div style="margin-left: 0.5rem;">0.00</div>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @foreach ($errors->all() as $message)
                <p style="color: red;">{{ $message }}</p>
            @endforeach

            <div class="button-container">
                <button type="button" class="add-row-btn">
                    Add New Row
                </button>

                <button type="submit" class="submit-btn" id="submitButton" disabled>Submit Journal Entry</button>
                <p id="balanceWarning" style="color: red; display: none;">
                    Debits and credits must be equal before submitting.
                </p>
            </div>
        </form>
    </div>
</x-app-layout>

<script>
    document.getElementById('date').valueAsDate = new Date();
</script>
