<x-app-layout>
    @vite('resources/js/invoices/create.js')
    <div style="max-width: 1500px; width: 90%; margin: 2rem auto;">
        <form action="{{ route('invoices.store') }}" method="POST" style="display: grid; gap: 1rem;"
            enctype="multipart/form-data">
            @csrf
            <input type="date" name="issue_date" value="{{ old('date') ?? now()->format('Y-m-d') }}" required />
            <input type="file" name="image" id="image" required />
            <input name="description" id="description" required />
            <input name="invoice_number" id="invoice_number" required />

            <select name="transaction_type" required>
                <option value="purchases">Purchases</option>
                <option value="sales">Sales</option>
            </select>
            <select name="payment_method" required>
                <option selected disabled value="">Select Payment Type</option>
                <option value="cash">Cash</option>
                <option value="bank">Bank Transfer</option>
            </select>

            <select name="client" required>
                <option selected disabled value="">Select Client</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}">
                        {{ $client->name }}</option>
                @endforeach
            </select>
            <div class="line_items">
                <table>
                    <thead></thead>
                    <tbody id="table-body">
                        <!-- Rows here -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5"><strong>Total</strong></td>
                            <td id="total-sum">0.00</td>
                        </tr>
                    </tfoot>
                </table>
                <button type="button" class="btn add-row">Add row</button>
            </div>
            <input type="checkbox" name="pending" />
            <button type="submit">Submit</button>

        </form>
    </div>
</x-app-layout>
