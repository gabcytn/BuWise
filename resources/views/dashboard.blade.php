<x-app-layout>
    <form action="/logout" method="POST" style="display: flex; justify-content: center; align-items:center; height: calc(100dvh - 5rem);">
        @csrf
        <button type="submit" style="padding: 1rem 1.85rem; border-radius: 5px; background-color: green; color: white; cursor: pointer; font-size: 1rem;">Logout</button>
    </form>
</x-app-layout>
