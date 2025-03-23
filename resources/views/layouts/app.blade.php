<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Inter and Poppins -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

        <title>{{ config('app.name', 'Laravel') }}</title>

        @vite(['resources/css/welcome.css', 'resources/js/welcome.js'])
    </head>
    <body>
        <!-- Navbar -->
        @include('layouts.navigation')

        <section class="main-section">
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
            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </section>

        <!-- FONT AWESOME -->
        <script src="https://kit.fontawesome.com/4bc1035a4c.js" crossorigin="anonymous"></script>
    </body>
</html>
