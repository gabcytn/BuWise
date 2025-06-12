<x-root-layout>
    @vite(['resources/css/layout.css', 'resources/js/welcome.js', 'resources/js/echo.js'])
    <!-- Navbar -->
    @include('layouts.navigation')

    <section class="main-section">
        @include('components.header')
        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </section>
</x-root-layout>
