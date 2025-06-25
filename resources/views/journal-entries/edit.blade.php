<x-app-layout>
    @vite(['resources/css/journal-entries/create.css', 'resources/js/journal-entries/edit.js'])
    <select id="select-clone" required class="d-none">
        <option value="" selected disabled>Select an account</option>
        @foreach ($accounts as $account)
            <option value="{{ $account->id }}">
                {{ $account->code . ' ' . $account->name }}
            </option>
        @endforeach
    </select>
    <div class="container">
        <h2 id="page-title">Journal Entry</h2>
        <hr />
        <div class="pad">
            <form id="journalForm" method="POST" action="{{ route('journal-entries.update', $journal_entry) }}"
                data-row-count="{{ count($ledger_entries) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="input-wrapper">
                        <label for="reference_no">Reference no.</label>
                        <input value="{{ $journal_entry->reference_no }}" type="number" name="reference_no"
                            id="reference_no" />
                    </div>
                    <div class="input-wrapper">
                        <label for="date">Date</label>
                        <input value="{{ $journal_entry->date }}" type="date" name="date" id="date" />
                    </div>
                    <div class="input-wrapper">
                        <label for="transaction-type">Transaction Type</label>
                        <select required id="transaction-type" name="transaction_type">
                            <option {{ $journal_entry->transaction_type === 'sales' ? 'selected' : '' }} value="sales">
                                Sales</option>
                            <option {{ $journal_entry->transaction_type === 'purchases' ? 'selected' : '' }}
                                value="purchases">
                                Purchases</option>
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
                                <th>Account</th>
                                <th>Description</th>
                                <th>Debits</th>
                                <th>Credits</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="journalBody">
                            @foreach ($ledger_entries as $key => $ledger_entry)
                                <tr class="journal-row">
                                    <td>
                                        <select id="select-account" required name="{{ 'account_' . $key + 1 }}">
                                            @foreach ($accounts as $account)
                                                <option
                                                    {{ $ledger_entry->account_id === $account->id ? 'selected' : '' }}
                                                    value="{{ $account->id }}">
                                                    {{ $account->code . ' ' . $account->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input name="{{ 'description_' . $key + 1 }}" class="row-description"
                                            value="{{ $ledger_entry->description }}" placeholder="Description" />
                                    </td>
                                    @php
                                        $debit_value =
                                            $ledger_entry->entry_type === 'debit' ? $ledger_entry->amount : '';
                                    @endphp
                                    <td><input type="number" min="0" step="0.01"
                                            name="{{ 'debit_' . $key + 1 }}" value="{{ $debit_value }}"
                                            {{ $debit_value === '' ? 'disabled' : '' }} placeholder="0.00" />
                                    </td>
                                    @php
                                        $credit_value =
                                            $ledger_entry->entry_type === 'credit' ? $ledger_entry->amount : '';
                                    @endphp
                                    <td><input type="number" min="0" step="0.01"
                                            name="{{ 'credit_' . $key + 1 }}" value="{{ $credit_value }}"
                                            {{ $credit_value === '' ? 'disabled' : '' }} placeholder="0.00" />
                                    </td>
                                    <td></td>
                                    <input type="hidden" name="{{ 'row_id_' . $key + 1 }}"
                                        value="{{ $key + 1 }}" />
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="totals-row">
                                <td colspan="2">
                                    <div style="text-align: right; margin-right: 0.5rem;">Total</div>
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

                <div class="button-container">
                    <button type="submit" class="submit-btn" id="submitButton">Update</button>
                    <p id="balanceWarning" style="color: red; display: none;">
                        Debits and credits must be equal before submitting.
                    </p>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
