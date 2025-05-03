<x-app-layout>
    @php
        $headers = ['Journal ID', 'Client Name', 'Transaction Type', 'Description', 'Amount', 'Date', 'Action'];
        function truncate($text, $max = 50)
        {
            return strlen($text) > 50 ? substr($text, 0, $max) . '...' : $text;
        }
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
        <select class="select-clients"
            style="width: 100%; background-color: var(--clear-white); padding: 0.65rem; margin-top: 1rem; border: none; border-radius: 5px;">
            <option value="all">All Clients</option>
            @php
                $roleId = request()->user()->role_id;
                if ($roleId === \App\Models\Role::ACCOUNTANT) {
                    $clients = request()->user()->clients;
                } elseif ($roleId !== \App\Models\Role::CLIENT) {
                    $clients = request()->user()->accountant->clients;
                }
            @endphp
            @foreach ($clients as $client)
                <option {{ request()->query('filter') === $client->id ? 'selected' : '' }} value="{{ $client->id }}">
                    {{ $client->name }}
                </option>
            @endforeach
        </select>
        @if (count($entries) > 0)
            <x-table-management :headers=$headers>
                @foreach ($entries as $key => $entry)
                    <tr class="journal-row" style="cursor: pointer;" data-url={{ "journal-entries/$entry->id" }}>
                        <td>
                            <p>{{ $entry->id }}</p>
                        </td>
                        <td>
                            <p>{{ $entry->client->name }}</p>
                        </td>
                        <td>
                            <p>{{ $entry->transactionType->name }}</p>
                        </td>
                        <td>
                            <p>{{ truncate($entry->description) }}</p>
                        </td>
                        <td>
                            <p>&#8369;{{ $entry->ledger_entries_max_amount }}
                        </td>
                        <td>
                            @php
                                $date = \Carbon\Carbon::parse($entry->date);
                                $formattedDateTime = $date->format('F d, Y');
                            @endphp
                            <p>{{ $formattedDateTime }}</p>
                        </td>
                        <td class="action-column">
                            <div>
                                <form action="{{ route('journal-entries.destroy', $entry) }}" method="POST"
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
            {{ $entries->links() }}
        @else
            <p>No entry yet.</p>
        @endif
    </div>

    <x-confirmable-dialog title="Confirm Delete" affirmText="Delete" denyText="Back"></x-confirmable-dialog>
</x-app-layout>
