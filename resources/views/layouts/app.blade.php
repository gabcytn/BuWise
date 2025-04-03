<x-root-layout>
    @vite(['resources/css/welcome.css', 'resources/js/welcome.js'])
    <!-- Navbar -->
    @include('layouts.navigation')

    <section class="main-section">
        <div class="header-container">
            <header class="header-sm">
                <i class="fa-solid fa-bars"></i>
                <div class="header-side">
                    <i class="fa-solid fa-bell"></i>
                    <div class="header-side__account" style="cursor: pointer;">
                        <img src="https://placehold.co/50" alt="Profile Image" />
                        <div class="header-side__account--details">
                            <p id="account-name">{{ request()->user()->name }}</p>
                            <p id="account-role">{{ request()->user()->role->name }}</p>
                        </div>
                        <i class="fa-solid fa-circle-chevron-down"></i>
                    </div>
                </div>
            </header>
        </div>
        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </section>
</x-root-layout>
