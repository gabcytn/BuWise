<x-app-layout title="Journals">
    @php
        $headers = ['Account ID', 'Account Name', 'Account Group Name', 'Notes', 'Debit', 'Credit'];
        $journalStatus = $journalEntry->status;
    @endphp
    @vite('resources/css/journal-entries/show.css')
    <div class="container">
        <h2 id="page-title">Ledger Entries</h2>
        <p id="description">{{ $journalEntry->description }}</p>
        <x-table-management :headers=$headers>
            @foreach ($ledgerEntries as $entry)
                <tr class="journal-row">
                    <td>{{ $entry->account_code }}</td>
                    <td title="{{ $entry->account_name }}">{{ $entry->account_name }}</td>
                    <td>{{ ucfirst($entry->account_group_name) }}</td>
                    <td title="{{ $entry->description }}">{{ truncate($entry->description) }}</td>
                    <td>{{ $entry->debit ? number_format($entry->debit, 2) : '' }}</td>
                    <td>{{ $entry->credit ? number_format($entry->credit, 2) : '' }}</td>
                </tr>
            @endforeach
        </x-table-management>
        <div class="buttons-row">
            <button type="button" id="back-button">Back</button>
            @if ($journalStatus === 'pending' || $journalStatus === 'rejected')
                <form action="{{ route('journal-entries.approve', $journalEntry) }}" method="POST">
                    @csrf
                    <button id="approve-button" type="submit">Approve</button>
                </form>
            @endif

            @if ($journalStatus === 'pending' || $journalStatus === 'approved')
                <form action="{{ route('journal-entries.reject', $journalEntry) }}" method="POST">
                    @csrf
                    <button id="reject-button" type="submit">Reject</button>
                </form>
            @endif
            @if ($journalEntry->type === 'journal')
                <form action="{{ route('journal-entries.edit', $journalEntry) }}" method="GET">
                    <button id="edit-button" type="submit">Edit</button>
                </form>
            @endif
        </div>
    </div>
    <script>
        document.querySelector("#back-button").addEventListener("click", () => {
            window.history.back();
        });
    </script>
</x-app-layout>
