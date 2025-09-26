@vite(['resources/css/components/import-invoice-dialog.css', 'resources/js/components/import-invoice-dialog.js'])
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
