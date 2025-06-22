<x-app-layout>
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
                    <button type="button" id="dropdown-button">&#11206;</button>
                    <a class="dropdown-import d-none" href="#">
                        Import Excel
                    </a>
                    <button type="button" id="vertical-ellipsis">&#8942;</button>
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
                            <td>
                                <p>{{ $entry->reference_no }}</p>
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
                                <p>&#8369;{{ number_format($entry->amount, 2) }}
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
                                            <i class="fa-regular fa-trash-can"
                                                style="color: #ff0000; cursor: pointer"></i>
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
    </div>
    <dialog id="csv-dialog">
        <h2>Upload CSV</h2>
        <form action="/journal-entries/csv" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="dialog-select-container">
                <input name="csv" required id="csv" type="file" />
                <select name="transaction_type" required>
                    <option value="sales">Sales</option>
                    <option value="purchases">Purchases</option>
                </select>
                <select name="client" required>
                    <option value="" selected disabled>Select a client</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">
                            {{ $client->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="dialog-button-container">
                <button type="submit">Submit</button>
                <button type="button">Cancel</button>
            </div>
        </form>
    </dialog>
    <x-confirmable-dialog title="Confirm Delete" affirmText="Delete" denyText="Back"></x-confirmable-dialog>
</x-app-layout>
