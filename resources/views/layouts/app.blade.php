<x-root-layout>
    @vite(['resources/css/layout.css', 'resources/js/welcome.js', 'resources/js/echo.js'])
    <!-- Navbar -->
    @include('layouts.navigation')

    <section class="main-section">
        @include('components.header')
        <!-- Page Content -->
        <main>
            <div class="d-none banner" id="notification-banner">
                <div class="banner-content">
                    <p id="notification-text">You have received a notification!</p>
                    <i class="fa-solid fa-xmark"></i>
                </div>
            </div>
            @if ($errors->any())
                <div class="banner" id="error-banner">
                    <div class="banner-content">
                        <div class="banner-texts">
                            <h3>Error!</h3>
                            <p>{{ $errors->first() }}</p>
                        </div>
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
            @endif
            @if (session('status'))
                <div class="banner" id="success-banner">
                    <div class="banner-content">
                        <div class="banner-texts">
                            <h3>Success!</h3>
                            <p>{{ session('status') }}</p>
                        </div>
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
            @endif
            {{ $slot }}
        </main>
    </section>
    <script>
        document.querySelectorAll(".banner .fa-xmark").forEach(item => {
            item.addEventListener("click", (e) => {
                e.target.parentNode.parentNode.classList.add("d-none");
            })
        });
    </script>
</x-root-layout>
