<x-app-layout>
    <div class="container" style="max-width: 1000px; width: 90%; margin: 0 auto;">
        @if (count($invoices) > 0)
            <h2>Invoice Management</h2>
            <p>Manage your clients' invoices</p>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                @foreach ($invoices as $invoice)
                    <div>
                        <p>{{ $invoice->id }}</p>
                        <img style="width: 150px; height: 150px;" src="{{ $invoice->image }}"</img>
                    </div>
                @endforeach
            </div>
        @else
            <h2>No invoices</h2>
        @endif
    </div>
</x-app-layout>
