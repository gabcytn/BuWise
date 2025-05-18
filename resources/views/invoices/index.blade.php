<x-app-layout>
    <div class="container" style="max-width: 1000px; width: 90%; margin: 0 auto;">
        @if (count($invoices) > 0)
            <h2>Invoice Management</h2>
            <p>Manage your clients' invoices</p>
            @foreach ($invoices as $invoice)
                <hr />
                <p>{{ $invoice->id }}</p>
                <img src="{{ $invoice->image }}"</img>
            @endforeach
        @else
            <h2>No invoices</h2>
        @endif
    </div>
</x-app-layout>
