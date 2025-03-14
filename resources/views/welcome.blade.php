<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config("app.name") }}</title>
    </head>
    <body>
        <header>
            <nav>
                <!-- IF USER IS AUTHENTICATED -->
                @auth
                    <a href="{{ url('/dashboard') }}">Dashboard</a>

                <!-- IF USER IS NOT AUTHENTICATAED -->
                @else
                    <a href="/login">Log in</a>
                    <a href="/register">Register</a>
                @endauth
            </nav>
        </header>

        <!-- MAIN CONTENT HERE -->
        <main>
            <h1 style="text-align: center;">Landing Page Here</h1>
        </main>
    </body>
</html>
