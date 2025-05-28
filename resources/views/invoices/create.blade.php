<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Invoice</title>
    @vite('resources/css/invoices/add.css')
</head>
<x-app-layout>
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
                    <input type="date" name="due_date" />
                </div>
                <div class="form-row">
                    <label>Transaction Type</label>
                    <select required name="transaction_type">
                        <option selected disabled value="">Select Transaction Type</option>
                        <option value="{{ \App\Models\TransactionType::SALES }}">Sales</option>
                        <option value="{{ \App\Models\TransactionType::PURCHASE }}">Purchases</option>
                    </select>
                </div>
                <div class="form-row">
                    <label>Invoice Number</label>
                    <input type="text" placeholder="Enter Invoice Number" name="invoice_number" />
                </div>
                <div class="form-row">
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
                <div class="form-row">
                    <label>Tax Type</label>
                    <select name="tax">
                        <option value="0" selected>No Tax</option>
                        @foreach ($taxes as $tax)
                            <option value="{{ $tax->id }}">
                                {{ strtoupper($tax->name) . ' (' . $tax->value . ' %)' }}</option>
                        @endforeach
                    </select>
                    <small>Only select this option if tax applies as a whole.</small>
                </div>
                <div class="form-row">
                    <label>Discount Type</label>
                    <select name="discount_type">
                        <option value="no_discount">No Discount</option>
                        <option value="senior_citizen">Senior Citizen (20%)</option>
                        <option value="pwd">PWD (20%)</option>
                    </select>
                    <small>Only select this option if a discount applies as a whole.</small>
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
            <tbody>
                <tr>
                    <td>Sample Item</td>
                    <td><input type="number" value="0" /></td>
                    <td><input type="number" value="0.00" step="0.01" /></td>
                    <td><input type="number" value="0.00" step="0.01" /></td>
                    <td>
                        <select>
                            <option>Select Discount</option>
                        </select>
                    </td>
                    <td>
                        <select>
                            <option>Select Tax Type</option>
                        </select>
                    </td>
                    <td><input type="number" value="0.00" step="0.01" /></td>
                </tr>
            </tbody>
        </table>

        <div class="actions">
            <button class="btn add-row">+ Add New Row</button>
            <div class="submit-buttons">
                <button class="btn btn-primary">Proceed to Journal</button>
                <button class="btn btn-secondary">Cancel</button>
            </div>
        </div>
    </form>
</x-app-layout>
