<x-app-layout>
    @php
        $headers = ['Account ID', 'Account Name', 'Account Group Name', 'Notes', 'Tax', 'Debit', 'Credit'];
        $journalStatus = $journalEntry->status->id;
        $approved = \App\Models\Status::APPROVED;
        $pending = \App\Models\Status::PENDING;
        $rejected = \App\Models\Status::REJECTED;
    @endphp
    @vite('resources/css/journal-entries/show.css')
    <div class="container">
        <h2 id="page-title" style="margin-top: 1.25rem; margin-bottom: 0.5rem;">Ledger Entries</h2>
        <p>Description: {{ $journalEntry->description }}</p>
        @switch($journalStatus)
            @case($approved)
                <p>Approved</p>
            @break

            @case($pending)
                <p>Approved</p>
            @break

            @case($rejected)
                <p>Rejected</p>
            @break

            @default
            @break
        @endswitch
        <x-table-management :headers=$headers>
            @foreach ($ledgerEntries as $entry)
                <tr class="journal-row">
                    <td>{{ $entry->account_code }}</td>
                    <td>{{ $entry->account_name }}</td>
                    <td>{{ ucfirst($entry->account_group_name) }}</td>
                    <td title="{{ $entry->description }}">{{ truncate($entry->description) }}</td>
                    <td>{{ $entry->tax_value }}</td>
                    <td>{{ $entry->debit }}</td>
                    <td>{{ $entry->credit }}</td>
                </tr>
            @endforeach
        </x-table-management>
        @if (session('status'))
            <p style="color: var(--green);">{{ session('status') }}</p>
        @endif
        @if ($journalStatus === $pending || $journalStatus === $rejected)
            <form action="{{ route('journal-entries.approve', $journalEntry) }}" method="POST">
                @csrf
                <button type="submit">Approve</button>
            </form>
        @endif

        @if ($journalStatus === $pending || $journalStatus === $approved)
            <form action="{{ route('journal-entries.reject', $journalEntry) }}" method="POST">
                @csrf
                <button type="submit">Reject</button>
            </form>
        @endif
        <button type="button" id="back-button">Back</button>
        <form action="{{ route('journal-entries.edit', $journalEntry) }}" method="GET">
            <button type="submit">Edit</button>
        </form>
    </div>
    <script>
        document.querySelector("#back-button").addEventListener("click", () => {
            window.history.back();
        });
    </script>
</x-app-layout>
