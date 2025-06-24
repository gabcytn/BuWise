@vite('resources/css/dialog/dialog.css')
<dialog id="{{ $id }}">
    <h2 id="user-dialog-title">{{ $title }}</h2>
    <form id="user-dialog-form" id="{{ $formId }}" method="POST" action="{{ route($route) }}"
        enctype="multipart/form-data">
        @csrf
        {{ $slot }}
    </form>
</dialog>
