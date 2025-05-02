@php
    $headers = ['ACCOUNT NAME', 'ACCOUNT CODE', 'ACCOUNT TYPE'];
@endphp
<x-app-layout>
    <div class="container" style="max-width: 1000px; width: 90%; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between;">
            <h2 id="page-title">All Accounts</h2>
            <button>New account</button>
        </div>
        <x-table-management :headers=$headers>
            @foreach ($accounts as $account)
                <tr>
                    <td>{{ $account->name }}</td>
                    <td>{{ $account->id }}</td>
                    <td>{{ ucfirst($account->accountGroup->name) }}</td>
                </tr>
            @endforeach
        </x-table-management>
        {{ $accounts->links() }}
    </div>
</x-app-layout>
