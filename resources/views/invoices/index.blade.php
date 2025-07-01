<x-app-layout title="Invoices">
    @vite(['resources/css/invoices/invoice.css', 'resources/js/invoices/index.js'])

    <div class="dashboard-wrapper">
        <dialog id="scan-invoice-dialog">
            <h2>Scan an Invoice</h2>
            <form action="{{ route('invoices.scan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div>
                    <input type="file" name="invoice" required />
                    <select name="transaction_type" required>
                        <option value="" selected disabled>Choose Transaction Type</option>
                        <option value="sales">Sales</option>
                        <option value="purchases">Purchases</option>
                    </select>
                    <select name="client" required>
                        <option value="" selected disabled>Choose Client</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="buttons-container">
                    <button type="submit">Scan</button>
                    <button type="button">Cancel</button>
                </div>
            </form>
        </dialog>

        <!-- Header Row: Title + New Invoice Button + Extra Buttons -->
        <div class="invoice-header-row">
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
                            <a href="#" id="from-gallery">From Gallery</a>
                            <a href="{{ route('invoices.create') }}">Manual Invoice</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Invoice Table + Toolbar -->
        <div class="invoice-container">
            <form class="invoice-toolbar">
                <select name="period"
                    style="background-image: url('/images/byicon.png'), url('/images/menudown.png');">
                    <option value="all_time">All Time</option>
                    <option value="this_year">This Year</option>
                </select>
                <select name="status"
                    style="background-image: url('/images/filterbyicon.png'), url('/images/menudown.png');">
                    <option value="all" selected>All Status</option>
                    <option value="approved">Approved</option>
                    <option value="pending">Pending</option>
                    <option value="rejected">Rejected</option>
                </select>
                <select name="client"
                    style="background-image: url('/images/allclientsicon.png'), url('/images/menudown.png');">
                    <option value="all">All Clients</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>

                <!-- Search Input with Icon Inside -->
                <div class="search-input" style="position: relative;">
                    <img src="{{ asset('images/magnify.png') }}" alt="Search" class="search-icon"
                        style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); height: 16px;">
                    <input type="search" name="search" placeholder="Search Invoice No." style="padding-left: 30px;" />
                </div>
                <button id="run-button" type="submit">Run</button>
            </form>

            @if (count($invoices) > 0)
                <div class="table-wrapper">
                    <table class="invoice-table">
                        <thead>
                            <tr>
                                <th>Invoice Number</th>
                                <th>Client</th>
                                <th>Billing Date</th>
                                <th>Transaction Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Processed By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->reference_no }}</td>
                                    <td>{{ $invoice->client->name }}</td>
                                    <td>{{ $invoice->date }}</td>
                                    <td>{{ ucfirst($invoice->kind) }}</td>
                                    <td>{{ number_format($invoice->amount, 2) }}</td>
                                    <td>
                                        <span class="status {{ strtolower($invoice->status) }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                    <td title="{{ $invoice->creator->name }}">{{ truncate($invoice->creator->name) }}
                                    </td>
                                    <td>
                                        <a href="{{ route('invoices.show', $invoice->id) }}" class="action-view"
                                            title="View">
                                            <img src="{{ asset('images/viewicon.png') }}" alt="View" />
                                        </a>
                                        <a href="{{ route('invoices.edit', $invoice->id) }}" class="action-edit"
                                            title="Edit">
                                            <img src="{{ asset('images/editicon.png') }}" alt="Edit" />
                                        </a>
                                        <form method="POST" action="{{ route('invoices.destroy', $invoice->id) }}"
                                            style="display:inline;" onsubmit="return confirm('Delete this invoice?')">
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
                </div>
                {{ $invoices->links() }}
            @else
                <h2 style="text-align: center;">No invoices found.</h2>
            @endif
        </div>
    </div>
</x-app-layout>
