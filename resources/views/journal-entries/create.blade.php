<x-app-layout title="Journals">
    @vite(['resources/css/journal-entries/create.css', 'resources/js/journal-entries/create.js'])
    <div class="container">
        <h2 id="page-title">Add Entry</h2>
        <hr />
        <div class="pad">
            <select style="display: none;" id="select-account" required>
                <option value="" selected disabled>Select an account</option>
                @foreach ($accounts as $account)
                    <option value="{{ $account->id }}">{{ $account->code . ' ' . $account->name }}</option>
                @endforeach
            </select>
            <form id="journalForm" method="POST" action="{{ route('journal-entries.store') }}">
                @csrf
                <div class="row">
                    <div>
                        <label for="reference_no">Reference No.</label>
                        <input type="number" name="reference_no" id="reference_no" />
                    </div>
                    <div class="input-wrapper">
                        <label for="date">Date</label>
                        <input type="date" name="date" id="date"
                            value="{{ old('date') ?? now()->format('Y-m-d') }}" />
                    </div>
                    <div class="input-wrapper">
                        <label for="transaction-type">Transaction Type</label>
                        <select required id="transaction-type" name="transaction_type">
                            <option value="" selected disabled>Select a transaction type</option>
                            <option value="sales">Sales</option>
                            <option value="purchases">Purchases</option>
                        </select>
                    </div>
                    <div class="input-wrapper client-select-wrapper">
                        <label for="client-select">Client</label>
                        <select name="client_id" id="client-select" required="">
                            <option value="" disabled selected>Select a client</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-wrapper notes-wrapper">
                    <label for="description">Notes</label>
                    <textarea id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                </div>
                <div class="table-wrapper">
                    <table id="journalTable">
                        <thead>
                            <tr>
                                <th>Account</th>
                                <th>Description</th>
                                <th>Debits</th>
                                <th>Credits</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="journalBody">
                            <!-- Rows will be added here -->
                        </tbody>
                        <tfoot>
                            <tr class="totals-row">
                                <td colspan="2">
                                    <div style="text-align: right; margin-right: 0.5rem;">Total (PHP) with tax</div>
                                </td>
                                <td>
                                    <div style="margin-left: 0.5rem;" id="actual-total-debits">0.00</div>
                                </td>
                                <td>
                                    <div style="margin-left: 0.5rem;" id="actual-total-credits">0.00</div>
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
                        <i class="fa-solid fa-plus"></i>Add New Row
                    </button>
                </div>
            </form>
        </div>
        <hr />

        <div class="button-container outside">
            <div class="buttons-row">
                <button form="journalForm" type="submit" class="submit-btn" id="submitButton" disabled>Submit Journal
                    Entry</button>
                <button type="button" class="cancel-button">Cancel</button> <br />
            </div>
            <p id="balanceWarning" style="color: red; display: none;">
                Debits and credits must be equal before submitting.
            </p>
        </div>
    </div>
</x-app-layout>

<!-- confirmation dialog -->
<dialog id="confirm-dialog">
    <h2><i class="fa-solid fa-circle-info"></i>Confirm Creation</h2>
    <div class="content">
        <h3>Are you sure?</h3>
        <p>Publishing this entry will automatically create a ledger entry. Are you sure you want to proceed?</p>
        <div class="buttons-row">
            <button class="confirm-button">Confirm</button>
            <button class="cancel-button">Cancel</button>
        </div>
    </div>
</dialog>
