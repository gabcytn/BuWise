@php
    $headers = ['Date', 'Description', 'Account Name', 'Account Group', 'Debit', 'Credit'];
    function truncate($text, $max = 50)
    {
        return strlen($text) > 50 ? substr($text, 0, $max) . '...' : $text;
    }

    function formatDate($date)
    {
        $res = \Carbon\Carbon::parse($date);
        return $res->format('F d, Y');
    }
@endphp
<x-app-layout>
    <div class="container" style="max-width: 1000px; width: 90%; margin: 0 auto;">
        <h2>{{ $overall }}</h2>
        <x-table-management :headers=$headers>
            <tr>
                <td></td>
                <td>Opening balance</td>
                <td></td>
                <td></td>
                <td>{{ $entry_type === 'debit' ? number_format($initial_balance, 2) : '' }}</td>
                <td>{{ $entry_type === 'credit' ? number_format($initial_balance, 2) : '' }}</td>
            </tr>
            @foreach ($data as $datum)
                <tr>
                    <td>{{ formatDate($datum->journal_date) }}</td>
                    <td>{{ truncate($datum->journal_description) }}</td>
                    <td>{{ $datum->acc_name }}</td>
                    <td>{{ ucfirst($datum->acc_group) }}</td>
                    <td>{{ $datum->debit ? number_format($datum->debit, 2) : '' }}</td>
                    <td>{{ $datum->credit ? number_format($datum->credit, 2) : '' }}</td>
                </tr>
            @endforeach
            <tr style="background-color: var(--off-white);">
                <td></td>
                <td><strong>TOTAL</strong></td>
                <td></td>
                <td></td>
                <td><strong>{{ number_format($total_debits, 2) }}</strong></td>
                <td><strong>{{ number_format($total_credits, 2) }}</strong></td>
            </tr>
        </x-table-management>
    </div>
</x-app-layout>
