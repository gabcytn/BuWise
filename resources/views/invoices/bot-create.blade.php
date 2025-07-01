<!DOCTYPE html>
<html>

<head>
    @include('includes.head')
    <style>
        * {
            font-family: "Plus Jakarta Sans";
        }
    </style>
</head>

<body>
    @vite('resources/js/invoices/create.js')
    <div style="max-width: 1500px; width: 90%; margin: 2rem auto;">
        <form action="{{ route('invoices.store') }}" method="POST" style="display: grid; gap: 1rem;"
            enctype="multipart/form-data">
            @csrf
            <label for="date">Date</label>
            <input id="date" type="date" name="issue_date" value="{{ old('date') ?? now()->format('Y-m-d') }}"
                required />
            <label for="image">Image</label>
            <input type="file" name="image" id="image" required />
            <label for="description">Description</label>
            <input name="description" id="description" required />
            <label for="invoice_number">Invoice Number</label>
            <input name="invoice_number" id="invoice_number" required />

            <label for="transaction-type">Transaction Type</label>
            <select id="transaction-type" name="transaction_type" required>
                <option value="purchases">Purchases</option>
                <option value="sales">Sales</option>
            </select>
            <label for="payment-method-select" id="payment-method">Payment Method</label>
            <select id="payment-method-select" name="payment_method" required>
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

            <label for="client-select">Client</label>
            <select id="client-select" name="client" required>
                <option selected disabled value="">Select Client</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}">
                        {{ $client->name }}</option>
                @endforeach
            </select>

            <div class="tax-wrapper">
                <label for="withholding-tax">Withholding Tax (optional)</label>
                <input type="number" step="0.00" name="withholding_tax" id="withholding-tax" />
            </div>

            <div class="line_items">
                <table>
                    <thead></thead>
                    <tbody id="table-body">
                        <!-- Rows here -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5"><strong>Total</strong></td>
                            <td id="total-sum">0.00</td>
                        </tr>
                    </tfoot>
                </table>
                <button type="button" class="btn add-row">Add row</button>
            </div>
            <input type="checkbox" name="pending" />
            <button type="submit">Submit</button>

        </form>
    </div>
</body>

</html>
