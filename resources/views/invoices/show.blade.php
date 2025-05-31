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
    @vite('resources/css/invoices/show.css')
    <div class="container">
        <div class="p-3 invoice-header">
            <h2>View Invoice</h2>
            <select>
                <option>{{ $invoice->client->name }}</option>
            </select>
        </div>
        <hr />
        <div class="p-3">
            <div class="details-container">
                <div class="details-container__left">
                    <img src="{{ $invoice->image }}" id="invoice-image" />
                </div>
                <div class="details-container__right">
                    <div class="input-container">
                        <label for="date">Issue Date</label>
                        <input disabled id="date" type="date" value="{{ $invoice->date }}" />
                    </div>
                    <div class="input-container">
                        <label for="transaction_type">Transaction Type</label>
                        <select id="transaction_type">
                            <option>{{ ucfirst($invoice->kind) }}</option>
                        </select>
                    </div>
                    <div class="input-container">
                        <label for="invoice_number">Invoice Number</label>
                        <input disabled id="invoice_number" type="number" value="{{ $invoice->reference_no }}" />
                    </div>
                    <div class="input-container">
                        <label for="payment_method">Payment Method</label>
                        <select id="payment_method">
                            <option>{{ ucfirst($invoice->payment_method) }}</option>
                        </select>
                    </div>
                    <div class="input-container">
                        <label for="description">Description</label>
                        <input disabled id="description" value="{{ $invoice->description }}" />
                    </div>
                </div>
            </div>
            <x-table-management :headers=$headers>
                @foreach ($invoice->invoice_lines as $item)
                    <tr>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->unit_price }}</td>
                        <td>{{ $item->discount ? $item->discount . '%' : '0%' }}</td>
                        <td>
                            <select>
                                @if ($item->tax_id)
                                    <option data-tax-value="{{ $item->tax->value }}">
                                        {{ $item->tax->name . ' (' . $item->tax->value . '%)' }}
                                    </option>
                                @else
                                    <option data-tax-value="0">No Tax</option>
                                @endif
                            </select>
                        </td>
                        <td>{{ calculateTotal($item) }}</td>
                    </tr>
                @endforeach
            </x-table-management>
        </div>
        <hr />
        <div class="p-3 button-container">
            <form class="edit-form" action="{{ route('invoices.edit', $invoice) }}">
                <button type="submit">Edit</button>
            </form>
            <form class="ledger-form" action="{{ route('journal-entries.show', $invoice) }}">
                <button type="submit">View in Ledger</button>
            </form>
        </div>
    </div>
</x-app-layout>
