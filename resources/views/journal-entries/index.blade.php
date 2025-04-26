<x-app-layout>
    <div
        style="min-height: calc(100dvh - 5rem); display: flex; flex-direction: column; justify-content: center; align-items: center; font-size: 1.5rem;">
        Display Journal Entries Here
        <form action="{{ route('journal-entries.create') }}" method="GET">
            <button type="submit"
                style="padding: 0.75rem 1.25rem; margin: 1rem 0; background-color: var(--green); border: none; border-radius: 0.25rem; color: var(--off-white); outline: none; cursor: pointer;">
                Create
            </button>
        </form>
    </div>
</x-app-layout>
