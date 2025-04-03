<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('includes.head')
</head>

<body>
    @vite('resources/css/root.css')
    {{ $slot }}
</body>

</html>
