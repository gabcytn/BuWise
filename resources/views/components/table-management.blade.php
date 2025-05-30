@props(['headers'])
@vite(['resources/css/components/table-management.css'])
<div class="table-wrapper">
    <table class="table-management">
        <thead>
            <tr>
                @foreach ($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
