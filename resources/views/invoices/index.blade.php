<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Management</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite('resources/css/invoices/invoice.css')
</head>

<x-app-layout>
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
                            <th>Invoice ID</th>
                            <th>Billing Date</th>
                            <th>Name of Supplier</th>
                            <th>Invoice Number</th>
                            <th>Transaction Type</th>
                            <th>Tax</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->id }}</td>
                                <td>{{ $invoice->billing_start }} - {{ $invoice->billing_end }}</td>
                                <td>{{ $invoice->supplier }}</td>
                                <td>{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->transaction_type }}</td>
                                <td>{{ number_format($invoice->tax, 2) }}</td>
                                <td>{{ number_format($invoice->amount, 2) }}</td>
                                <td>
                                    <span class="status {{ strtolower($invoice->status) }}">{{ $invoice->status }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('invoices.show', $invoice->id) }}" class="action-view" title="View"><i class="fa fa-eye"></i></a>
                                    <a href="{{ route('invoices.edit', $invoice->id) }}" class="action-edit" title="Edit"><i class="fa fa-pen"></i></a>
                                    <form method="POST" action="{{ route('invoices.destroy', $invoice->id) }}" style="display:inline;" onsubmit="return confirm('Delete this invoice?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-delete" title="Delete"><i class="fa fa-trash"></i></button>
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
