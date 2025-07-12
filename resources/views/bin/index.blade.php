@php
    $headers = ['Item', 'Reference No.', 'Client', 'Date', 'Transaction Type', 'Amount (₱)', 'Created By', 'Time Left'];
    $sort_by = request()->query('sort-by');
    $filter_by = request()->query('filter-by');
    $search = request()->query('search');
@endphp
@vite(['resources/css/bin/index.css', 'resources/js/bin/index.js'])
<x-app-layout title="Recycle Bin">
    <div class="container">
        <div class="content">
            <form class="filter-row">
                <div class="filter-row__left">
                    <select name="sort-by">
                        <option {{ $sort_by === 'deleted_at' ? 'selected' : '' }} value="deleted_at">Sort by: Deletion
                            Date</option>
                        <option {{ $sort_by === 'reference_no' ? 'selected' : '' }} value="reference_no">Sorty by:
                            Reference No.</option>
                    </select>
                    <select name="filter-by">
                        <option {{ $filter_by === 'all' ? 'selected' : '' }} value="all">Filter by: All types</option>
                        <option {{ $filter_by === 'journal' ? 'selected' : '' }} value="journal">Filter by: Journal
                        </option>
                        <option {{ $filter_by === 'invoice' ? 'selected' : '' }} value="invoice">Filter by: Invoice
                        </option>
                    </select>
                </div>
                <div class="filter-row__right">
                    <input type="search" placeholder="Search reference no." name="search"
                        value="{{ $search }}" />
                    <button type="submit">Run</button>
                </div>
            </form>
            <x-table-management :headers=$headers>
                @foreach ($transactions as $item)
                    @php
                        $until_date = \Carbon\Carbon::createFromDate($item->deleted_at)->addDays(30);
                        $diff = round(abs($until_date->diffInDays(\Carbon\Carbon::now())), 0);
                    @endphp
                    <tr data-id="{{ $item->id }}">
                        <td><input type="checkbox" /></td>
                        <td>{{ $item->reference_no }}</td>
                        <td>{{ $item->client->name }}</td>
                        <td>{{ \Carbon\Carbon::createFromDate($item->date)->format('M d, Y') }}</td>
                        <td>{{ ucfirst($item->kind) }}</td>
                        <td>{{ number_format($item->amount, 2) }}</td>
                        <td>{{ $item->creator->name }}</td>
                        <td class="time">{{ $diff }} days</td>
                    </tr>
                @endforeach
            </x-table-management>
            @if (request()->user()->role_id === \App\Models\Role::ACCOUNTANT)
                <div class="action-buttons">
                    <button id="restore-btn">Restore</button>
                    <button id="delete-btn">Delete</button>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

<dialog id="confirm-delete">
    <h2>Permanently Delete</h2>
    <div class="confirmation-message">
        <h3>Are you sure you want to delete?</h3>
        <p>This action cannot be undone.</p>
        <div class="buttons-row">
            <button type="submit" id="dialog-delete">Delete</button>
            <button type="button">Cancel</button>
        </div>
    </div>
</dialog>
<dialog id="confirm-restore">
    <h2>Confirm Restoration</h2>
    <div class="confirmation-message">
        <h3>Are you sure you want to restore items?</h3>
        <p>Items will be cleared from your recycle bin.</p>
        <div class="buttons-row">
            <button type="submit">Restore</button>
            <button type="button">Cancel</button>
        </div>
    </div>

</dialog>
