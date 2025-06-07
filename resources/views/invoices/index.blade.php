<x-app-layout>
    @vite('resources/css/invoices/invoice.css')

    <div class="dashboard-wrapper">

        <!-- Header Row: Title + New Invoice Button + Extra Buttons -->
        <div class="invoice-header-row" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
            <div class="invoice-header">
                <h2>Invoice Management</h2>
                <p>Efficiently handle your client’s invoices. View each invoice by clicking the corresponding row.</p>
            </div>

            <div style="display: flex; align-items: center; gap: 1rem;">
                <div class="top-toolbar">
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

                <!-- Extra Right Buttons -->
                <div class="right" style="display: flex; align-items: center; gap: 0.5rem;">
                    <button class="more-btn" aria-label="More options">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#1f2d3d">
                            <circle cx="12" cy="5" r="2" />
                            <circle cx="12" cy="12" r="2" />
                            <circle cx="12" cy="19" r="2" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Invoice Table + Toolbar -->
        <div class="invoice-container">
            <div class="invoice-toolbar">
                <select style="background-image: url('/images/byicon.png'), url('/images/menudown.png');">
                    <option>Period: All Time</option>
                </select>
                <select style="background-image: url('/images/filterbyicon.png'), url('/images/menudown.png');">
                    <option>Filter by: Status</option>
                </select>
                <select style="background-image: url('/images/allclientsicon.png'), url('/images/menudown.png');">
                    <option>Select Client</option>
                </select>

                <!-- Search Input with Icon Inside -->
                <div class="search-input" style="position: relative;">
                    <img src="{{ asset('images/magnify.png') }}" alt="Search" class="search-icon" style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); height: 16px;">
                    <input type="text" placeholder="Search Invoices..." style="padding-left: 30px;" />
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
                                    <span class="status {{ strtolower($invoice->status) }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                <td>
    <a href="{{ route('invoices.show', $invoice->id) }}" class="action-view" title="View">
        <img src="{{ asset('images/viewicon.png') }}" alt="View" />
    </a>
    <a href="{{ route('invoices.edit', $invoice->id) }}" class="action-edit" title="Edit">
        <img src="{{ asset('images/editicon.png') }}" alt="Edit" />
    </a>
    <form method="POST" action="{{ route('invoices.destroy', $invoice->id) }}" style="display:inline;" onsubmit="return confirm('Delete this invoice?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="action-delete" title="Delete">
            <img src="{{ asset('images/deleteicon.png') }}" alt="Delete" />
        </button>

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
