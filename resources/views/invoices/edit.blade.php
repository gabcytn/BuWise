@php
    $headers = ['Item', 'Quantity', 'Unit Price', 'Less: Discount', 'Tax', 'Total Amount (₱)'];
@endphp
<x-app-layout>
    @vite(['resources/css/invoices/show.css', 'resources/js/invoices/edit.js'])
    <form class="container" action="{{ route('invoices.update', $invoice) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="p-3 invoice-header">
            <h2 id="page-title">Edit Invoice</h2>
            <select name="client" disabled>
                <option value="{{ $invoice->client_id }}" selected>{{ $invoice->client->name }}</option>
            </select>
        </div>
        <hr />
        <div class="p-3">
            <div class="details-container">
                <div class="details-container__left">
                    <img src="{{ $image }}" id="invoice-image" />
                </div>
                <div class="details-container__right">
                    <div class="input-container">
                        <label for="date">Issue Date</label>
                        <input id="date" type="date" value="{{ $invoice->date }}" name="issue_date" required />
                    </div>
                    <div class="input-container">
                        <label for="transaction_type">Transaction Type</label>
                        <select id="transaction_type" name="transaction_type" required disabled>
                            <option value="{{ $invoice->kind }}">{{ ucfirst($invoice->kind) }}</option>
                        </select>
                    </div>
                    <div class="input-container">
                        <label for="invoice_number">Invoice Number</label>
                        <input id="invoice_number" type="number" value="{{ $invoice->reference_no }}" required
                            name="invoice_number" />
                    </div>
                    <div class="input-container">
                        <label id="payment-method" for="payment-method-select">Payment Method</label>
                        <select name="payment_method" id="payment-method-select" required>
                            <option selected disabled value="">Select Payment Type</option>
                            <option {{ $invoice->payment_method === 'cash' ? 'selected' : '' }} value="cash">Cash
                            </option>
                            <option {{ $invoice->payment_method === 'checkings' ? 'selected' : '' }} value="checkings">
                                Bank
                                (Checkings)
                            <option {{ $invoice->payment_method === 'savings' ? 'selected' : '' }} value="savings">Bank
                                (Savings)
                            </option>
                            <option {{ $invoice->payment_method === 'petty_cash' ? 'selected' : '' }}
                                value="petty_cash">
                                Petty
                                Cash</option>
                            <option {{ $invoice->payment_method === 'receivable' ? 'selected' : '' }}
                                value="receivable">
                                Accounts Receivable</option>
                            <option {{ $invoice->payment_method === 'payable' ? 'selected' : '' }} value="payable">
                                Accounts
                                Payable
                            </option>
                        </select>
                    </div>
                    <div class="input-container">
                        <label for="description">Description</label>
                        <input id="description" value="{{ $invoice->description }}" name="description" />
                    </div>
                </div>
            </div>
            <x-table-management :headers=$headers>
                @foreach ($invoice->invoice_lines as $key => $item)
                    <tr class="invoice-item">
                        <td><input value="{{ $item->item_name }}" name="{{ 'item_' . $key + 1 }}" required /></td>
                        <td><input type="number" value="{{ $item->quantity }}" name="{{ 'qty_' . $key + 1 }}"
                                required /></td>
                        <td><input type="number" value="{{ $item->unit_price }}"
                                name="{{ 'unit_price_' . $key + 1 }}" required />
                        </td>
                        <td><input type="number" value="{{ $item->discount ? $item->discount : 0 }}"
                                name="{{ 'discount_' . $key + 1 }}" /></td>
                        <td><input type="number" value="{{ $item->tax ? $item->tax : 0 }}"
                                name="{{ 'tax_' . $key + 1 }}" /></td>
                        <td id="{{ 'row-total-' . $key + 1 }}"></td>
                    </tr>
                @endforeach
                <tfoot>
                    <tr>
                        <td colspan="5"><strong>Total</strong></td>
                        <td id="overall-total"><strong>0.00</strong></td>
                    </tr>
                </tfoot>
            </x-table-management>
            @if ($errors->any())
                <p style="color: red;">{{ $errors->first() }}</p>
            @endif
        </div>
        <hr />
        <div class="p-3 button-container">
            <div class="edit-form">
                <button type="submit">Update</button>
            </div>
        </div>
    </form>
</x-app-layout>
