<x-app-layout title="Invoices">
    @php
        $headers = [
            'Invoice Number',
            'Client',
            'Billing Date',
            'Transaction Type',
            'Amount',
            'Status',
            'Processed By',
            'Action',
        ];
    @endphp
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

            <div class="create-invoice-buttons">
                <form action="{{ route('invoices.create') }}">
                    <button type="submit">Create</button>
                </form>
                <button id="import-btn">Import</button>
            </div>

        </div>

        <!-- Invoice Table + Toolbar -->
        <div class="invoice-container">
            <form class="invoice-toolbar">
                <div class="left">
                    <select name="period">
                        <option value="all_time" {{ request()->query('period') === 'all_time' ? 'selected' : '' }}>
                            All time</option>
                        <option value="this_year" {{ request()->query('period') === 'this_year' ? 'selected' : '' }}>
                            This Year</option>
                        <option value="this_month" {{ request()->query('period') === 'this_month' ? 'selected' : '' }}>
                            This Month</option>
                        <option value="this_week" {{ request()->query('period') === 'this_week' ? 'selected' : '' }}>
                            This Week</option>
                        <option value="last_week" {{ request()->query('period') === 'last_week' ? 'selected' : '' }}>
                            Last Week</option>
                        <option value="last_month" {{ request()->query('period') === 'last_month' ? 'selected' : '' }}>
                            Last Month</option>
                    </select>
                    <select name="status">
                        <option value="all" {{ request()->query('status') === 'all' ? 'selected' : '' }}> All Status
                        </option>
                        <option value="approved" {{ request()->query('status') === 'approved' ? 'selected' : '' }}>
                            Approved</option>
                        <option value="pending" {{ request()->query('status') === 'pending' ? 'selected' : '' }}>
                            Pending</option>
                        <option value="rejected" {{ request()->query('status') === 'rejected' ? 'selected' : '' }}>
                            Rejected</option>
                    </select>
                    <select name="client">
                        <option value="all" {{ request()->query('client') === 'all' ? 'selected' : '' }}>All Clients
                        </option>
                        @foreach ($clients as $client)
                            <option {{ request()->query('client') === $client->id ? 'selected' : '' }}
                                value="{{ $client->id }}">
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="right">
                    <div class="search-input" style="position: relative;">
                        <i class="fa-solid fa-magnifying-glass search-icon"></i>
                        <input type="search" name="search" placeholder="Search Invoice No."
                            value="{{ request()->query('search') }}" />
                    </div>
                    <div>
                        <button id="run-button" type="submit">Run</button>
                    </div>
                </div>
            </form>

            @if (count($invoices) > 0)
                <x-table-management :headers="$headers">
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->reference_no }}</td>
                            <td>{{ $invoice->client->name }}</td>
                            <td>{{ $invoice->date }}</td>
                            <td>{{ ucfirst($invoice->kind) }}</td>
                            <td>&#8369;{{ number_format($invoice->amount, 2) }}</td>
                            <td>
                                <strong class="status {{ strtolower($invoice->status) }}">
                                    {{ ucfirst($invoice->status) }}
                                </strong>
                            </td>
                            <td title="{{ $invoice->creator->name }}">{{ truncate($invoice->creator->name) }}
                            </td>
                            <td class="action-column">
                                <div>
                                    <button title="Edit">
                                        <a href="{{ route('invoices.edit', $invoice->id) }}">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                    </button>
                                    <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST"
                                        id="{{ 'form-' . $invoice->id }}"
                                        onsubmit="return confirm('Delete Invoice No. {{ $invoice->reference_no }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete">
                                            <i class="fa-regular fa-trash-can"
                                                style="color: #ff0000; cursor: pointer"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-table-management>
                {{ $invoices->links() }}
            @else
                <h2 style="text-align: center;">No invoices found.</h2>
            @endif
        </div>
    </div>
</x-app-layout>
