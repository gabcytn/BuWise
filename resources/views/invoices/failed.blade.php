<x-app-layout title="Failed Invoices">
    @vite(['resources/css/invoices/failed.css', 'resources/js/invoices/failed.js'])
    <div class="container">
        <div class="page-header">
            <h1>Failed Invoices</h1>
            <p>Manage your invoices that failed to auto scan.</p>
        </div>
        <div class="page-content">
            <form class="page-content__filters" method="GET">
                <div class="page-content__filters--input">
                    <select name="order_by">
                        <option {{ !request()->query('order_by') ? 'selected' : '' }} disabled value="">Order By
                        </option>
                        <option {{ request()->query('order_by') === 'date' ? 'selected' : '' }} value="date">Date
                        </option>
                        <option {{ request()->query('order_by') === 'client' ? 'selected' : '' }} value="client">Client
                        </option>
                    </select>
                    <select name="client">
                        <option value="" {{ !request()->query('client') ? 'selected' : '' }} disabled>Select a
                            Client</option>
                        @foreach ($clients as $client)
                            <option {{ request()->query('client') === $client->id ? 'selected' : '' }}
                                value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="page-content__filters--button">
                    <button type="submit">Run</button>
                </div>
            </form>
            <div class="page-content__invoices">
                @if (count($invoices) > 0)
                    @foreach ($invoices as $item)
                        <div class="invoice-card" data-invoice-id="{{ $item->id }}">
                            <img src="{{ asset('storage/temp/' . $item->filename) }}" />
                            <p><strong>Client: </strong>{{ $item->client->name }}</p>
                            <p id="created-at">{{ $item->created_at }}</p>
                        </div>
                    @endforeach
                    {{ $invoices->links() }}
                @else
                    <h1 style="text-align: center; width: 100%;">No records found.</h1>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

<dialog id="delete-item-dialog">
    <h2>Delete this item</h2>
    <form action="#" method="POST">
        @csrf
        <h3>Are you sure you want to delete this item?</h3>
        <p>This action is irreversible.</p>
        <button type="submit">Delete</button>
        <button type="button">Cancel</button>
    </form>
</dialog>
