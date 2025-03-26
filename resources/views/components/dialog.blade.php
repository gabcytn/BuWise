@vite('resources/css/dialog/dialog.css')
<dialog id="{{ $id }}">
    <h2>{{ $title }}</h2>
    <form id="{{ $formId }}" method="POST" action="{{ route($route) }}" enctype="multipart/form-data">
        @csrf
        {{ $slot }}
    </form>
</dialog>
