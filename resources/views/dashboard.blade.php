<x-app-layout>
    <form action="/logout" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
</x-app-layout>
