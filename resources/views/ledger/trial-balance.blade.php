@php
    $headers = ['Account ID', 'Account Name', 'Debit', 'Credit'];
@endphp
@vite(['resources/css/ledger/trialBalance.css', 'resources/js/ledger/trialBalance.js'])
<x-app-layout>
    <div class="container">
        <h2>Trial Balance</h2>
        <form action="" method="GET" id="report-form">
            <select name="client" required>
                <option value="" selected disabled>Choose a client</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}"
                        {{ request()->query('client') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                @endforeach
            </select>
            <select id="date-range-select">
                <option value="all_time" {{ request()->query('start_date') ? '' : 'selected' }}>All time</option>
                <option value="custom" id="custom-option" {{ request()->query('start_date') ? 'selected' : '' }}>Custom
                </option>
            </select>
            <input id="start_date" name="start_date" type="hidden" value="{{ request()->query('start_date') }}" />
            <input id="end_date" name="end_date" type="hidden" value="{{ request()->query('end_date') }}" />
            <button type="submit">Run report</button>
        </form>
        @if (request()->query('client') && count($data) > 0)
            <x-table-management :headers=$headers>
                @foreach ($data as $datum)
                    @php
                        $balance = $datum->debit - $datum->credit;
                    @endphp
                    <tr>
                        <td>{{ $datum->acc_id }}</td>
                        <td>{{ $datum->acc_name }}</td>
                        <td>{{ $balance > 0 ? number_format($balance, 2) : '' }}</td>
                        <td>{{ $balance <= 0 ? number_format(abs($balance), 2) : '' }}</td>
                    </tr>
                @endforeach
            </x-table-management>
        @elseif(request()->query('client') && count($data) == 0)
            <h2 style="text-align: center;">This client has no transaction yet.</h2>
        @endif
        @if ($errors->any())
            <p style="color: red; font-size: 0.85rem; margin: 0.5rem 0;">{{ $errors->first() }}</p>
        @endif
    </div>
    <dialog id="set-custom-date-range-dialog">
        <h2>Choose starting and ending date</h2>
        <form id="date-range-form">
            <div class="date-input-wrapper">
                <input type="date" name="start" id="start" required
                    value="{{ request()->query('start_date') }}" />
                <input type="date" name="end" id="end" required
                    value="{{ request()->query('end_date') }}" />
            </div>
            <div class="submit-btn-wrapper">
                <button type="submit">Run</button>
                <button type="button">Cancel</button>
            </div>
        </form>
    </dialog>
</x-app-layout>
