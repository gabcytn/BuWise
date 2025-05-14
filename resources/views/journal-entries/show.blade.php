<x-app-layout>
    @php
        $headers = ['Account ID', 'Account Name', 'Account Group Name', 'Debit', 'Credit'];
    @endphp
    @vite('resources/css/journal-entries/show.css')
    <div class="container">
        <h2 id="page-title" style="margin-top: 1.25rem; margin-bottom: 0.5rem;">Ledger Entries</h2>
        <p>Description: {{ $description }}
        <p>
            <x-table-management :headers=$headers>
                @foreach ($ledgerEntries as $entry)
                    <tr class="journal-row">
                        <td>{{ $entry->account_code }}</td>
                        <td>{{ $entry->account_name }}</td>
                        <td>{{ ucfirst($entry->account_group_name) }}</td>
                        <td>{{ $entry->debit }}</td>
                        <td>{{ $entry->credit }}</td>
                    </tr>
                @endforeach
            </x-table-management>
            <button type="button" id="back-button">Back</button>
    </div>
    <script>
        document.querySelector("#back-button").addEventListener("click", () => {
            window.history.back();
        });
    </script>
</x-app-layout>
