<x-app-layout>
    @vite('resources/css/invoices/invoice.css')
    <div class="dashboard-wrapper">
        <div class="invoice-container">
            <div class="invoice-header">
                <h2>Invoice Management</h2>
                <p>Efficiently handle your client’s invoices. View each invoice by clicking the corresponding row.</p>
            </div>

            <div class="invoice-toolbar">
                <select>
                    <option>Period: All Time</option>
                </select>
                <select>
                    <option>Filter by: Status</option>
                </select>
                <select>
                    <option>Select Client</option>
                </select>

                <div class="search-input">
                    <input type="text" placeholder="Search Invoices..." />
                </div>

                <div class="dropdown">
                    <button class="btn-new-invoice dropdown-toggle">
                        <i class="fa fa-plus"></i> New Invoice
                    </button>
                    <div class="dropdown-menu">
                        <a href="#">From Gallery</a>
                        <a href="{{ route('invoices.create') }}">Manual Invoice</a>
                    </div>
                </div>
            </div>

            @if (count($invoices) > 0)
                <table class="invoice-table">
                    <thead>
                        <tr>
                            <th>Invoice Number</th>
                            <th>Billing Date</th>
                            <th>Transaction Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->reference_no }}</td>
                                <td>{{ $invoice->date }}</td>
                                <td>{{ ucfirst($invoice->kind) }}</td>
                                <td>{{ number_format($invoice->amount, 2) }}</td>
                                <td>
                                    <span
                                        class="status {{ strtolower($invoice->status) }}">{{ ucfirst($invoice->status) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('invoices.show', $invoice->id) }}" class="action-view"
                                        title="View"><i class="fa fa-eye"></i></a>
                                    <a href="{{ route('invoices.edit', $invoice->id) }}" class="action-edit"
                                        title="Edit"><i class="fa fa-pen"></i></a>
                                    <form method="POST" action="{{ route('invoices.destroy', $invoice->id) }}"
                                        style="display:inline;" onsubmit="return confirm('Delete this invoice?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-delete" title="Delete"><i
                                                class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <h2>No invoices found.</h2>
            @endif
        </div>
    </div>
</x-app-layout>
