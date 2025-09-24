<x-app-layout title="Journals">
    @php
        $headers = [
            'Reference No.',
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

    @vite(['resources/css/journal-entries/index.css', 'resources/js/journal-entries/index.js', 'resources/css/journal-entries/index-table.css', 'resources/js/journal-entries/index-table.js'])
    <div class="container">
        <form class="create-journal-form" action="{{ route('journal-entries.create') }}" method="GET">
            <div class="first-texts">
                <h2 id="page-title">{{ $title }}</h2>
                <p>{{ $subtitle }}</p>
            </div>
            <div class="button-container">
                @if (request()->routeIs('journal-entries.index'))
                    <button type="submit">Create</button>
                    <button type="button" class="dropdown-import">Import</button>
                @endif
            </div>
        </form>
        <div class="journal-container">
            <form class="select-container" action="" method="GET">
                <div class="select-container__left">
                    @if (request()->routeIs('journal-entries.index'))
                        <select class="select" name="period">
                            <option value="this_year"
                                {{ request()->query('period') === 'this_year' ? 'selected' : '' }}>
                                This Year</option>
                            <option value="this_month"
                                {{ request()->query('period') === 'this_month' ? 'selected' : '' }}>
                                This Month</option>
                            <option value="this_week"
                                {{ request()->query('period') === 'this_week' ? 'selected' : '' }}>
                                This Week</option>
                            <option value="last_week"
                                {{ request()->query('period') === 'last_week' ? 'selected' : '' }}>
                                Last Week</option>
                            <option value="last_month"
                                {{ request()->query('period') === 'last_month' ? 'selected' : '' }}>
                                Last Month</option>
                        </select>
                    @endif
                    <select class="select select-clients" name="client">
                        <option value="all" {{ request()->query('client') === 'all' ? 'selected' : '' }}>All Clients
                        </option>
                        @foreach ($clients as $client)
                            <option {{ request()->query('client') === $client->id ? 'selected' : '' }}
                                value="{{ $client->id }}">
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                    <select class="select" name="transaction_type">
                        <option value="all" {{ request()->query('transaction_type') === 'all' ? 'selected' : '' }}>
                            All Transactions</option>
                        <option value="sales" {{ request()->query('transaction_type') == 'sales' ? 'selected' : '' }}>
                            Sales</option>
                        <option value="purchases"
                            {{ request()->query('transaction_type') == 'purchases' ? 'selected' : '' }}>
                            Purchases</option>
                    </select>
                </div>
                <div class="select-container__right">
                    <div class="search-container">
                        <input type="search" placeholder="Search Reference No." name="search"
                            value="{{ request()->query('search') }}" />
                    </div>
                    <div class="run-filter-container">
                        <button id="submit-filters">Run</button>
                    </div>
                </div>
            </form>
            @if (count($entries) > 0)
                <x-table-management :headers=$headers>
                    @foreach ($entries as $key => $entry)
                        <tr class="journal-row" style="cursor: pointer;" data-url={{ "journal-entries/$entry->id" }}>
                            <td>{{ $entry->reference_no }}</td>
                            <td>{{ $entry->client_name }}</td>
                            <td>{{ ucfirst($entry->transaction_type) }}</td>
                            <td>{{ truncate($entry->description) }}</td>
                            <td>&#8369;{{ number_format($entry->amount, 2) }}</td>
                            <td>{{ formatDate($entry->date) }}</td>
                            <td>{{ $entry->creator }}</td>
                            <td>
                                <strong class="{{ $entry->status }} status">{{ ucfirst($entry->status) }}</strong>
                            </td>
                            <td class="action-column">
                                <div>
                                    <button>
                                        <a href="{{ route('journal-entries.edit', $entry->id) }}">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                    </button>
                                    <form action="{{ route('journal-entries.destroy', $entry->id) }}" method="POST"
                                        id="{{ 'form-' . $entry->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button">
                                            <i class="fa-regular fa-trash-can"
                                                style="color: #ff0000; cursor: pointer"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-table-management>
                {{ $entries->links() }}
            @endif
        </div>
    </div>
    <x-csv-upload-dialog :clients="$clients" />
    <x-confirmable-dialog title="Confirm Delete" affirmText="Delete" denyText="Back"></x-confirmable-dialog>
</x-app-layout>
