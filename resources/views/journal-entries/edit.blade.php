<x-app-layout>
    @vite(['resources/css/journal-entries/create.css', 'resources/js/journal-entries/edit.js'])
    <select id="select-clone" required class="d-none">
        <option value="" selected disabled>Select an account</option>
        @foreach ($accounts as $account)
            <option value="{{ $account->id }}">
                {{ $account->id . ' ' . $account->name }}
            </option>
        @endforeach
    </select>
    <div class="container">
        <h2 id="page-title">Journal Entry</h2>
        <form id="journalForm" method="POST" action="{{ route('journal-entries.update', $journal_entry) }}"
            data-row-count="{{ count($ledger_entries) }}">
            @csrf
            @method('PUT')
            <div class="row">
                @if ($journal_entry->invoice_id !== null)
                    <div class="input-wrapper">
                        <label>Invoice ID</label>
                        <input type="text" disabled value="{{ $journal_entry->invoice_id }}" />
                    </div>
                @endif
                <div class="input-wrapper">
                    <label for="date">Date</label>
                    <input value="{{ $date }}" type="date" name="date" id="date" />
                </div>
                <div class="input-wrapper">
                    <label for="transaction-type">Transaction Type</label>
                    <select required id="transaction-type" name="transaction_type_id">
                        @foreach ($transactionTypes as $transactionType)
                            <option {{ $journal_entry->transaction_type_id === $transactionType->id ? 'selected' : '' }}
                                value="{{ $transactionType->id }}">{{ $transactionType->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-wrapper">
                    <label for="client-select">Client</label>
                    <select class="select-clients" name="client_id">
                        <option value="{{ $journal_entry->client_id }}">
                            {{ $journal_entry->client->name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="input-wrapper">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3" required>{{ $journal_entry->description }}</textarea>
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
                        @foreach ($ledger_entries as $key => $ledger_entry)
                            <tr>
                                <td>
                                    <select id="select-account" required name="{{ 'account_' . $key + 1 }}">
                                        @foreach ($accounts as $account)
                                            <option {{ $ledger_entry->account_id === $account->id ? 'selected' : '' }}
                                                value="{{ $account->id }}">
                                                {{ $account->id . ' ' . $account->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                @php
                                    $debit_value =
                                        $ledger_entry->entry_type_id === \App\Models\EntryType::DEBIT
                                            ? $ledger_entry->amount
                                            : '';
                                @endphp
                                <td><input type="number" min="0" step="0.01"
                                        name="{{ 'debit_' . $key + 1 }}" value="{{ $debit_value }}"
                                        {{ $debit_value === '' ? 'disabled' : '' }} placeholder="0.00" />
                                </td>
                                @php
                                    $credit_value =
                                        $ledger_entry->entry_type_id === \App\Models\EntryType::CREDIT
                                            ? $ledger_entry->amount
                                            : '';
                                @endphp
                                <td><input type="number" min="0" step="0.01"
                                        name="{{ 'credit_' . $key + 1 }}" value="{{ $credit_value }}"
                                        {{ $credit_value === '' ? 'disabled' : '' }} placeholder="0.00" />
                                </td>
                                <td></td>
                                <input type="hidden" name="{{ 'row_id_' . $key + 1 }}" value="{{ $key + 1 }}" />
                            </tr>
                        @endforeach
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

            <div class="button-container">
                <button type="button" class="add-row-btn">
                    Add New Row
                </button>
                <button type="submit" class="submit-btn" id="submitButton">Submit Journal Entry</button>
                <p id="balanceWarning" style="color: red; display: none;">
                    Debits and credits must be equal before submitting.
                </p>
            </div>

            @if ($errors->any())
                <p style="color: red;">{{ $errors->first() }}</p>
            @endif

        </form>
    </div>
    </div>
</x-app-layout>
