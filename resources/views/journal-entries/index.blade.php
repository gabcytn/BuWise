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

    @vite(['resources/css/journal-entries/index.css', 'resources/js/journal-entries/index.js', 'resources/css/journal-entries/index-table.css', 'resources/js/journal-entries/index-table.js'])
    <div class="container">
        <form class="create-journal-form" action="{{ route('journal-entries.create') }}" method="GET">
            <div class="first-texts">
                <h2 id="page-title">General Journal</h2>
                <p>Create journal entries and organize financial records.</p>
            </div>
            <div class="button-container">
                <button type="submit">Create</button>
                <button type="button" id="dropdown-button">&#11206;</button>
                <button type="button" id="vertical-ellipsis">&#8942;</button>
            </div>
        </form>
        <div class="journal-container">
            <form class="select-container" action="" method="GET">
                <div class="select-container__left">
                    <select class="select" name="period">
                        <option value="all_time">All time</option>
                        <option value="this_year">This year</option>
                        <option value="last_year">Last year</option>
                    </select>
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
                </div>
                <div class="select-container__right">
                    <div class="search-container">
                        <input type="search" placeholder="Search ID" name="search" />
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

    <x-confirmable-dialog title="Confirm Delete" affirmText="Delete" denyText="Back"></x-confirmable-dialog>
</x-app-layout>
