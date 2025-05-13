@php
    $headers = ['Account ID', 'Account Name', 'Balance', 'Entry Type'];
@endphp
<x-app-layout>
    <div class="container" style="max-width: 1000px; width: 90%; margin: 0 auto;">
        <h2>Trial Balance</h2>
        <form action="" method="GET">
            <select name="client" required>
                <option value="" selected disabled>Choose a client</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </select>
            <button type="submit">Run report</button>
        </form>
        @if (request()->query('client') && count($data) > 0)
            <x-table-management :headers=$headers>
                @foreach ($data as $datum)
                    <tr>
                        <td>{{ $datum->acc_id }}</td>
                        <td>{{ $datum->acc_name }}</td>
                        <td>&#8369;{{ number_format(abs($datum->balance), 2) }}</td>
                        <td>{{ $datum->balance < 0 ? 'Credit' : 'Debit' }}</td>
                    </tr>
                @endforeach
            </x-table-management>
        @elseif(request()->query('client') && count($data) == 0)
            <h2 style="text-align: center;">This client has no transaction yet.</h2>
        @endif
    </div>
</x-app-layout>
