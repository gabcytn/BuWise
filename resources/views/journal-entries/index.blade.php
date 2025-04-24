<x-app-layout>
    @if (count($entries) > 0)
        <p>More than 0</p>
    @else
        <p>Less than 0</p>
    @endif
</x-app-layout>
