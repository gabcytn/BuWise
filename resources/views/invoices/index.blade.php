@php
    $headers = ['Image', 'Invoice Number', 'Amount', 'Status', 'Action'];
@endphp
<x-app-layout>
    <div class="container" style="max-width: 1000px; width: 90%; margin: 0 auto;">
        @if (count($invoices) > 0)
            <h2>Invoice Management</h2>
            <p>Manage your clients' invoices</p>
            <select>
                <option value="All">All</option>
                <option value="Pending">Pending</option>
                <option value="Rejected">Rejected</option>
                <option value="Verified">Verified</option>
            </select>
            <x-table-management :headers=$headers>
                @foreach ($invoices as $invoice)
                    <tr>
                        <td><img width="40" height="40" style="border-radius:100%;"
                                src="{{ asset('storage/invoices/' . $invoice->image) }}" />
                        </td>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->amount }}</td>
                        <td>{{ ucfirst($invoice->description) }}</td>
                        <td class="action-column">
                            <div>
                                <a href="#">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>
                                <form action="#">
                                    <button type="submit"
                                        style="background-color: transparent; border: none; outline: none;">
                                        <i class="fa-regular fa-trash-can" style="color: #ff0000; cursor: pointer"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-table-management>
        @else
            <h2>No invoices</h2>
        @endif
    </div>
</x-app-layout>
