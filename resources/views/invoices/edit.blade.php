@php
    $headers = ['Item', 'Quantity', 'Unit Price', 'Less: Discount', 'Tax', 'Total Amount (₱)'];
    function calculateTotal($item)
    {
        $unit_price = $item->unit_price;
        $qty = $item->quantity;
        $discount = $item->discount;
        $total = $unit_price;
        if ($discount) {
            $total *= 1 - $discount / 100;
        }
        if ($item->tax_id) {
            $tax_value = $item->tax->value;
            $total += ($tax_value / 100) * $total;
        }

        $total *= $qty;
        return number_format($total, 2);
    }
@endphp
<x-app-layout>
    @vite(['resources/css/invoices/show.css'])
    <form class="container" action="{{ route('invoices.update', $invoice) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="p-3 invoice-header">
            <h2 id="page-title">Edit Invoice</h2>
            <select name="client" required>
                <option value="" disabled>Select Client</option>
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
                        <select id="transaction_type" name="transaction_type" required>
                            <option value="sales" {{ $invoice->kind === 'sales' ? 'selected' : '' }}>Sales</option>
                            <option value="purchases" {{ $invoice->kind === 'purchases' ? 'selected' : '' }}>Purchases
                            </option>
                        </select>
                    </div>
                    <div class="input-container">
                        <label for="invoice_number">Invoice Number</label>
                        <input id="invoice_number" type="number" value="{{ $invoice->reference_no }}" required
                            name="invoice_number" />
                    </div>
                    <div class="input-container">
                        <label for="description">Description</label>
                        <input id="description" value="{{ $invoice->description }}" name="description" />
                    </div>
                </div>
            </div>
            <x-table-management :headers=$headers>
                @foreach ($invoice->invoice_lines as $key => $item)
                    <tr>
                        <td><input value="{{ $item->item_name }}" name="{{ 'item_' . $key + 1 }}" required /></td>
                        <td><input value="{{ $item->quantity }}" name="{{ 'qty_' . $key + 1 }}" required /></td>
                        <td><input value="{{ $item->unit_price }}" name="{{ 'unit_price_' . $key + 1 }}" required />
                        </td>
                        <td><input value="{{ $item->discount ? $item->discount : 0 }}"
                                name="{{ 'discount_' . $key + 1 }}" required /></td>
                        <td>
                            <select name="{{ 'tax_' . $key + 1 }}" required>
                                <option value="0" data-tax-value="0">No Tax</option>
                                @foreach ($taxes as $tax)
                                    <option {{ $tax->id === $item->tax_id ? 'selected' : '' }}
                                        value="{{ $tax->id }}" data-tax-value="{{ $tax->value }}">
                                        {{ $tax->name . ' (' . $tax->value . '%)' }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>{{ calculateTotal($item) }}</td>
                    </tr>
                @endforeach
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
