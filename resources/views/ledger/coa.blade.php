@php
    $headers = ['ACCOUNT CODE', 'ACCOUNT NAME', 'ACCOUNT TYPE'];
@endphp
@vite('resources/js/ledger/coa.js')
<x-app-layout>
    <div class="container" style="max-width: 1000px; width: 90%; margin: 0 auto;">
        <div style="">
            <h2 id="page-title" style="margin-top: 1.5rem;">All Accounts</h2>
            <p style="font-size: 0.85rem;">View and manage all ledger accounts</p>
        </div>
        <x-table-management :headers=$headers>
            @foreach ($accounts as $account)
                <tr data-account-code="{{ $account->id }}" class="ledger-account" style="cursor: pointer;">
                    <td>{{ $account->id }}</td>
                    <td>{{ $account->name }}</td>
                    <td>{{ ucfirst($account->accountGroup->name) }}</td>
                </tr>
            @endforeach
        </x-table-management>
        {{ $accounts->links() }}
    </div>
</x-app-layout>
