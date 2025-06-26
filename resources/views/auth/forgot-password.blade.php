<x-root-layout>
    @vite('resources/css/auth/forgot-password.css')
<link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">

    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-wrapper">
            <img src="/images/buwiselogo.png" alt="BuWise Logo" class="logo">
        </div>
    </nav>

    <!-- Main Content -->
    <div class="forgot-wrapper">
        <h2 class="title">Forgot <span class="highlight">Password</span></h2>
        <p class="description">Enter your email address to receive a password reset link.</p>
        <div class="input-group">
    <label for="email" class="input-label">Email Address</label>

        <form action="/forgot-password" method="POST" class="forgot-form">
            @csrf
            
<div class="input-icon-wrapper">
    <i class="mdi mdi-email-outline"></i>
    <input type="email" name="email" class="input-field" placeholder="accountant@domain.com" required>
</div>

            <button type="submit" class="btn primary">Send Reset Link</button>
            <button type="button" class="btn secondary" onclick="history.back()">Go Back</button>
        </form>

        @if (session('status'))
            <p class="session-status">{{ session('status') }}</p>
        @endif

        @if ($errors->any())
            <p class="error-message">{{ $errors->first() }}</p>
        @endif
    </div>
</x-root-layout>
