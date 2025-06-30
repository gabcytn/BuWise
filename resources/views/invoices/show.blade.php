@php
    $headers = ['Item', 'Quantity', 'Unit Price', 'Less: Discount', 'Tax', 'Total Amount (₱)'];
    function calculateTotal($item)
    {
        $unit_price = $item->unit_price;
        $qty = $item->quantity;
        $total = $unit_price;
        if ($item->discount) {
            $total -= $item->discount;
        }
        if ($item->tax) {
            $total += $item->tax;
        }

        $total *= $qty;
        return number_format($total, 2);
    }
@endphp
<x-app-layout title="Invoices">
    @vite('resources/css/invoices/show.css')
    <div class="container">
        <div class="p-3 invoice-header">
            <h2>View Invoice</h2>
            <select disabled>
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
                        <select id="transaction_type" disabled>
                            <option>{{ ucfirst($invoice->kind) }}</option>
                        </select>
                    </div>
                    <div class="input-container">
                        <label for="invoice_number">Invoice Number</label>
                        <input disabled id="invoice_number" type="number" value="{{ $invoice->reference_no }}" />
                    </div>
                    <div class="input-container">
                        <label for="payment_method">Payment Method</label>
                        <select id="payment_method" disabled>
                            <option>{{ ucfirst($invoice->payment_method) }}</option>
                        </select>
                    </div>
                    <div class="input-container">
                        <label for="description">Description</label>
                        <input disabled id="description" value="{{ $invoice->description }}" />
                    </div>
                    @if ($invoice->kind === 'sales')
                        <div class="input-container">
                            <label for="withholding-tax">Withholding Tax</label>
                            <input disabled id="withholding-tax" value="{{ $invoice->withholding_tax }}" />
                        </div>
                    @endif
                </div>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            @foreach ($headers as $header)
                                <th>{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->invoice_lines as $item)
                            <tr>
                                <td>{{ $item->item_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->unit_price }}</td>
                                <td>{{ $item->discount ? $item->discount : '0' }}</td>
                                <td>{{ $item->tax ? $item->tax : '0' }}</td>
                                <td>{{ calculateTotal($item) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
    <script>
        const invoiceImage = document.querySelector("img#invoice-image");
        invoiceImage.addEventListener("click", () => {
            window.open(invoiceImage.src, "_blank");
        });
    </script>
</x-app-layout>
