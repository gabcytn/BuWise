<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="forgot-password.css">
    @vite("resources/css/auth/forgot-password.css")
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="forgot-password-container">
        <h2>Forgot Password</h2>
        <p>Enter Email</p>
        <form action="/forgot-password" method="POST">
            @csrf
            <div class="input-wrapper">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <button type="submit" class="send-btn">Send</button>
            <button type="button" class="go-back-btn">Go Back</button>
        </form>
        @if($errors->any())
            <p style="color: red;">{{ $errors->first() }}</p>
        @endif
    </div>
    <script>
        document.querySelector("button.go-back-btn").addEventListener("click", () => {
            window.location.href = window.origin + "/login";
        })
    </script>
</body>
</html>
