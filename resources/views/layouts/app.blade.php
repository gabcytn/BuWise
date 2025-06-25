<x-root-layout>
    @vite(['resources/css/layout.css', 'resources/js/welcome.js', 'resources/js/echo.js'])
    <!-- Navbar -->
    @include('layouts.navigation')

    <section class="main-section">
        @include('components.header')
        <!-- Page Content -->
        <main>
            <div class="d-none notification-banner" id="banner">
                <div class="banner-content">
                    <p>You have received a notification!</p>
                    <i class="fa-solid fa-xmark"></i>
                </div>
            </div>
            {{ $slot }}
        </main>
    </section>
    <script>
        document.querySelector("#banner .fa-xmark").addEventListener("click", (e) => {
            document.querySelector("#banner").classList.add("d-none");
        });
    </script>
</x-root-layout>
