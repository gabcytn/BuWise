<x-app-layout>
    @if (session('status'))
        <p style="color: red;">{{ session('status') }}</p>
    @endif
    @vite(['resources/css/journal-entries/create.css', 'resources/js/journal-entries/create.js'])
    <div class="container">
        <h2 id="page-title">Journal Entry</h2>
        <select style="display: none;" id="select-account" required>
            <option value="" selected disabled>Select an account</option>
            @foreach ($accounts as $account)
                <option value="{{ $account->id }}">{{ $account->id . ' ' . $account->name }}</option>
            @endforeach
        </select>
        <form id="journalForm" method="POST" action="{{ route('journal-entries.store') }}">
            @csrf
            <input type="date" name="date" id="date" style="margin-bottom: 0.5rem;" />
            <select name="client_id" required="" style="margin-bottom: 0.5rem;">
                <option value="" disabled selected>Select a client</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </select>
            <textarea name="description" rows="3" placeholder="Description" required></textarea>
            <select required name="transaction_type_id" style="margin-top: 0.5rem;">
                <option value="" selected disabled>Select a transaction type</option>
                @foreach ($transactionTypes as $transactionType)
                    <option value="{{ $transactionType->id }}">{{ $transactionType->name }}</option>
                @endforeach
            </select>
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
