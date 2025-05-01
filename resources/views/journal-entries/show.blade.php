<x-app-layout>
    @php
        $headers = ['ID', 'Account Name', 'Account Group Name', 'Debit', 'Credit'];
    @endphp
    @vite('resources/css/journal-entries/show.css')
    <div class="container">
        <h2 id="page-title" style="margin-top: 1.25rem; margin-bottom: 0.5rem;">Ledger Entries</h2>
        <p>Description: {{ $description }}
        <p>
            <x-table-management :headers=$headers>
                @foreach ($ledgerEntries as $key => $entry)
                    <tr class="journal-row">
                        <td>{{ $entry->id }}</td>
                        <td>{{ $entry->account_name }}</td>
                        <td>{{ ucfirst($entry->account_group_name) }}</td>
                        <td>{{ $entry->debit }}</td>
                        <td>{{ $entry->credit }}</td>
                    </tr>
                @endforeach
            </x-table-management>
        <form class="back-form" action="{{ route('journal-entries.index') }}" method="GET">
            <button type="submit">Back</button>
        </form>
    </div>
</x-app-layout>
