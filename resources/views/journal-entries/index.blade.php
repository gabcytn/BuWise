<x-app-layout>
    @php
        $headers = ['Journal ID', 'Client Name', 'Amount', 'Date', 'Action'];
    @endphp
    <div class="container" style="max-width: 1250px; width: 90%; margin: 0 auto;">
        <h2 id="page-title" style="margin-top: 1.25rem;">Journal Entry</h2>
        <form action="{{ route('journal-entries.create') }}" method="GET">
            <button type="submit"
                style="padding: 0.75rem 1.25rem; margin: 1rem 0; background-color: var(--green); border: none; border-radius: 0.25rem; color: var(--off-white); outline: none; cursor: pointer;">
                Create
            </button>
        </form>
        @if (count($entries) > 0)
            <x-table-management :headers=$headers>
                @foreach ($entries as $entry)
                    <tr>
                        <td>
                            <p>{{ $entry->id }}</p>
                        </td>
                        <td>
                            <p>{{ $entry->client->name }}</p>
                        </td>
                        <td>
                            <p>P100.00</p>
                        </td>
                        <td>
                            <p>{{ $entry->date }}</p>
                        </td>
                        <td class="action-column">
                            <div>
                                <a href="#">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>
                                <form action="#">
                                    <button type="submit"
                                        style="background-color: transparent; border: none; outline: none;">
                                        <i class="fa-regular fa-trash-can" style="color: #ff0000; cursor: pointer"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-table-management>
        @else
            <p>No entry yet.</p>
        @endif
    </div>
</x-app-layout>
