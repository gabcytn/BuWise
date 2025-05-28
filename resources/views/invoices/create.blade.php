<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Invoice</title>
    @vite(['resources/css/invoices/add.css', 'resources/js/invoices/create.js'])
</head>
<x-app-layout>
    <select name="tax" class="d-none">
        <option value="0" data-tax-value="0" selected>No Tax</option>
        @foreach ($taxes as $tax)
            <option value="{{ $tax->id }}" data-tax-value="{{ $tax->value }}">
                {{ strtoupper($tax->name) . ' (' . $tax->value . ' %)' }}</option>
        @endforeach
    </select>
    <input class="d-none" id="discount" type="number" placeholder="20" step="0.01" />
    <form class="invoice-container" method="POST" action="{{ route('invoices.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="page-header">
            <h2 class="page-title">Add Invoice</h2>
            <select name="client" required>
                <option selected disabled value="">Select Client</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="invoice-form">

            <div class="form-section left">
                <div class="image-placeholder">
                    <input name="image" type="file" required />
                </div>
            </div>

            <div class="form-section right">
                <div class="form-row">
                    <label>Issue Date</label>
                    <input type="date" name="issue_date" value="{{ now()->format('Y-m-d') }}" />
                </div>
                <div class="form-row">
                    <label>Due Date</label>
                    <input type="date" name="due_date" value="{{ now()->format('Y-m-d') }}" />
                </div>
                <div class="form-row">
                    <label>Transaction Type</label>
                    <select required name="transaction_type">
                        <option value="{{ \App\Models\TransactionType::PURCHASE }}">Purchases</option>
                        <option value="{{ \App\Models\TransactionType::SALES }}">Sales</option>
                    </select>
                </div>
                <div class="form-row">
                    <label>Invoice Number</label>
                    <input type="text" placeholder="Enter Invoice Number" name="invoice_number" required />
                </div>
                <div class="form-row" id="customer-supplier-input">
                    <label>Name of Supplier</label>
                    <input type="text" placeholder="Enter Name of Supplier" name="supplier" />
                </div>
                <div class="form-row">
                    <label>Invoice Status</label>
                    <label><input value="paid" type="radio" name="invoice_status" checked> Paid</label>
                    <label><input value="unpaid" type="radio" name="invoice_status">Unpaid</label>
                </div>
                <div class="form-row">
                    <label>Payment Method</label>
                    <select name="payment_method" required>
                        <option selected disabled value="">Select Payment Type</option>
                        <option value="cash">Cash</option>
                        <option value="bank">Bank Transfer</option>
                    </select>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <p style="color: red; font-size: 0.85rem; margin: 0.5rem;">{{ $errors->first() }}</p>
        @endif
        @if (session('status'))
            <p style="color: var(--green); font-size: 0.85rem; margin: 0.5rem;">{{ session('status') }}</p>
        @endif

        <table class="invoice-items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Net Amount</th>
                    <th>Less: Discount</th>
                    <th>Tax</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody id="table-body">
            </tbody>
            <tfoot>
                <td colspan="6"><strong>TOTAL</strong></td>
                <td id="total-sum">0.00</td>
            </tfoot>
        </table>

        <div class="actions">
            <button type="button" class="btn add-row">+ Add New Row</button>
            <div class="submit-buttons">
                <button type="submit" class="btn btn-primary">Proceed to Journal</button>
                <button type="button" class="btn btn-secondary">Cancel</button>
            </div>
        </div>
    </form>
</x-app-layout>
