<x-app-layout title="Invoices">
    @vite(['resources/css/invoices/add.css', 'resources/js/invoices/create.js'])

    <form class="invoice-container" method="POST" action="{{ route('invoices.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="page-header">
            <h2 class="page-title">Add Invoice</h2>
            <select name="client" required>
                <option selected disabled value="">Select Client</option>
                @foreach ($clients as $client)
                    <option {{ old('client') === $client->id ? 'selected' : '' }} value="{{ $client->id }}">
                        {{ $client->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="invoice-form">
            <div class="form-section left">
                <div class="image-placeholder">
                    <input name="image" type="file" required value="{{ old('image') }}" />
                </div>
            </div>

            <div class="form-section right">
                <div class="form-row">
                    <label>Issue Date</label>
                    <input type="date" name="issue_date" value="{{ old('date') ?? now()->format('Y-m-d') }}"
                        required />
                </div>
                <div class="form-row">
                    <label>Transaction Type</label>
                    <select required name="transaction_type">
                        <option {{ old('transaction_type') === 'purchases' ? 'selected' : '' }} value="purchases">
                            Purchases</option>
                        <option {{ old('transaction_type') === 'sales' ? 'selected' : '' }} value="sales">Sales
                        </option>
                    </select>
                </div>
                <div class="form-row">
                    <label>Invoice Number</label>
                    <input type="text" placeholder="Enter Invoice Number" name="invoice_number" required
                        value="{{ old('invoice_number') }}" />
                </div>
                <div class="form-row">
                    <label id="payment-method">Payment Method</label>
                    <select name="payment_method" required>
                        <option selected disabled value="">Select Payment Type</option>
                        <option {{ old('payment_method') === 'cash' ? 'selected' : '' }} value="cash">Cash</option>
                        <option {{ old('payment_method') === 'checkings' ? 'selected' : '' }} value="checkings">Bank
                            (Checkings)
                        <option {{ old('payment_method') === 'savings' ? 'selected' : '' }} value="savings">Bank
                            (Savings)
                        </option>
                        <option {{ old('payment_method') === 'petty_cash' ? 'selected' : '' }} value="petty_cash">Petty
                            Cash</option>
                        <option {{ old('payment_method') === 'receivable' ? 'selected' : '' }} value="receivable">
                            Accounts Receivable</option>
                        <option {{ old('payment_method') === 'payable' ? 'selected' : '' }} value="payable">Accounts
                            Payable
                        </option>
                    </select>
                </div>
                <div class="form-row">
                    <label for="description">Description</label>
                    <input type="text" name="description" id="description" value="{{ old('description') }}" />
                </div>
                <div class="form-row">
                    <label for="withholding-tax">Withholding Tax (optional)</label>
                    <input type="number" step="0.01" name="withholding_tax" id="withholding-tax"
                        value="{{ old('withholding_tax') }}" placeholder="0.00" />
                </div>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="invoice-items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Less: Discount</th>
                        <th>Tax (per qty)</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <!-- JS will append rows here -->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5"><strong>Total</strong></td>
                        <td id="total-sum">0.00</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="actions">
            <button type="button" class="btn add-row">+ Add New Row</button>
            <div class="submit-buttons">
                <button type="submit" class="btn btn-primary">Proceed to Journal</button>
                <button type="button" class="btn btn-secondary">Cancel</button>
            </div>
        </div>
    </form>
</x-app-layout>
