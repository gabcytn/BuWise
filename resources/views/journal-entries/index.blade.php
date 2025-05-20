<x-app-layout>
    @php
        $headers = [
            'Journal ID',
            'Client Name',
            'Transaction Type',
            'Description',
            'Amount',
            'Date',
            'Created By',
            'Status',
            'Action',
        ];
    @endphp

    @vite(['resources/css/journal-entries/index.css', 'resources/js/journal-entries/index.js'])
    <div class="container">
        <form class="create-journal-form" action="{{ route('journal-entries.create') }}" method="GET">
            <div class="first-texts">
                <h2 id="page-title">Journal Entry</h2>
                <p>Manage and access your clients' transactions</p>
            </div>
            <button type="submit">Create</button>
        </form>
        <form class="select-container">
            <select class="select select-type" name="type">
                <option value="all" {{ request()->query('type') === 'all' ? 'selected' : '' }}>Journals & Invoices
                </option>
                <option value="journals" {{ request()->query('type') === 'journals' ? 'selected' : '' }}>
                    From Journals Only
                </option>
                <option value="invoices" {{ request()->query('type') === 'invoices' ? 'selected' : '' }}>
                    From Invoices Only
                </option>
            </select>
            <select class="select select-invoice d-none" name="invoice">
                <option value="all" {{ request()->query('invoice') === 'all' ? 'selected' : '' }}>All</option>
                <option value="approved" {{ request()->query('invoice') === 'approved' ? 'selected' : '' }}>Approved
                </option>
                <option value="pending" {{ request()->query('invoice') === 'pending' ? 'selected' : '' }}>Pending
                </option>
                <option value="rejected" {{ request()->query('invoice') === 'rejected' ? 'selected' : '' }}>Rejected
                </option>
            </select>
            <select class="select select-clients" name="client">
                <option value="all" {{ request()->query('client') === 'all' ? 'selected' : '' }}>All Clients</option>
                @foreach ($clients as $client)
                    <option {{ request()->query('client') === $client->id ? 'selected' : '' }}
                        value="{{ $client->id }}">
                        {{ $client->name }}
                    </option>
                @endforeach
            </select>
            <button id="submit-filters">Run</button>
        </form>
        @if (count($entries) > 0)
            <x-table-management :headers=$headers>
                @foreach ($entries as $key => $entry)
                    <tr class="journal-row" style="cursor: pointer;" data-url={{ "journal-entries/$entry->id" }}>
                        <td>
                            <p>{{ $entry->id }}</p>
                        </td>
                        <td>
                            <p>{{ $entry->client_name }}</p>
                        </td>
                        <td>
                            <p>{{ $entry->transaction_type }}</p>
                        </td>
                        <td>
                            <p>{{ truncate($entry->description) }}</p>
                        </td>
                        <td>
                            <p>&#8369;{{ $entry->amount }}
                        </td>
                        <td>
                            <p>{{ formatDate($entry->date) }}</p>
                        </td>
                        <td>
                            <p>{{ $entry->creator }}</p>
                        </td>
                        <td>
                            <strong class="{{ $entry->status }}">{{ ucfirst($entry->status) }}</strong>
                        </td>
                        <td class="action-column">
                            <div>
                                <a href="{{ route('journal-entries.edit', $entry->id) }}">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('journal-entries.destroy', $entry->id) }}" method="POST"
                                    id="{{ 'form-' . $entry->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        style="display: flex; background-color: transparent; border: none; outline: none;">
                                        <i class="fa-regular fa-trash-can" style="color: #ff0000; cursor: pointer"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-table-management>
            @if ($errors->any())
                <p style="color: red; font-size: 0.85rem;">{{ $errors->first() }}</p>
            @endif
            {{ $entries->links() }}
        @endif
    </div>

    <x-confirmable-dialog title="Confirm Delete" affirmText="Delete" denyText="Back"></x-confirmable-dialog>
</x-app-layout>
