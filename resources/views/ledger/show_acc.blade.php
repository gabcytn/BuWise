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
        <x-table-management :headers=$headers>
            <tr>
                <td></td>
                <td>Opening balance</td>
                <td></td>
                <td></td>
                <td>0.00</td>
                <td>0.00</td>
            </tr>
            @foreach ($data as $datum)
                <tr>
                    <td>{{ formatDate($datum->journal_date) }}</td>
                    <td>{{ truncate($datum->journal_description) }}</td>
                    <td>{{ $datum->acc_name }}</td>
                    <td>{{ ucfirst($datum->acc_group) }}</td>
                    <td>{{ $datum->debit }}</td>
                    <td>{{ $datum->credit }}</td>
                </tr>
            @endforeach
        </x-table-management>
    </div>
</x-app-layout>
