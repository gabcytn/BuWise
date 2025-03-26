<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BuWise</title>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    @vite('resources/css/auth/register.css')
</head>
<body>
    <div class="register-container">
        <div class="left-section">
            <img src="{{ asset('images/imgbg.jpg') }}" alt="BuWise" class="register-image">
        </div>
        <div class="right-section">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <h2>Welcome</h2>
                <p>Simplifying and Automating Your Workflow</p>
                <div class="input-group">
                    <label for="name">Name</label>
                    <div class="input-wrapper">
                        <span class="icon"><i class="fas fa-user"></i></span>
                        <input id="name" type="text" name="name" required autocomplete="name">
                    </div>
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <div class="input-wrapper">
                        <span class="icon"><i class="fas fa-envelope"></i></span>
                        <input id="email" type="email" name="email" required autocomplete="username">
                    </div>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <span class="icon"><i class="fas fa-lock"></i></span>
                        <input id="password" type="password" name="password" required>
                    </div>
                </div>
                <div class="input-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="input-wrapper">
                        <span class="icon"><i class="fas fa-lock"></i></span>
                        <input id="password_confirmation" type="password" name="password_confirmation" required>
                    </div>
                </div>
                <button type="submit">{{ "Sign Up" }}</button>
            </form>
        </div>
    </div>
</body>
</html>
