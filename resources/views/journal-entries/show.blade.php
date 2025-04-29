<x-app-layout>
    @php
        $headers = ['ID', 'Account Name', 'Account Group Name', 'Debit', 'Credit'];
    @endphp
    <div class="container" style="max-width: 1250px; width: 90%; margin: 0 auto;">
        <h2 style="margin-top: 1.25rem; margin-bottom: 0.5rem;">Ledger Entries</h2>
        <p>Description: {{ $description }}
        <p>
            <x-table-management :headers=$headers>
                @foreach ($ledgerEntries as $entry)
                    <tr>
                        <td>{{ $entry->id }}</td>
                        <td>{{ $entry->account_name }}</td>
                        <td>{{ ucfirst($entry->account_group_name) }}</td>
                        <td>{{ $entry->debit }}</td>
                        <td>{{ $entry->credit }}</td>
                    </tr>
                @endforeach
            </x-table-management>
            <a href="{{ url()->previous() }}" style="text-decoration: none;">Back</a>
    </div>
</x-app-layout>
