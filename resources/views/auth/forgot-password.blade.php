<x-root-layout>
    @vite('resources/css/auth/forgot-password.css')
    <div class="forgot-password-container">
        <h2 id="title">Forgot Password</h2>
        <p id="subtitle">Enter Email</p>
        <form action="/forgot-password" method="POST">
            @csrf
            <div class="input-wrapper">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" name="email" placeholder="accountant@domain.com" required>
            </div>
            <button type="submit" class="send-btn">Send</button>
            <button type="button" class="go-back-btn">Go Back</button>
        </form>
        @if (session('status'))
            <p id="session-status">{{ session('status') }}</p>
        @endif
        @if ($errors->any())
            <p style="color: red;">{{ $errors->first() }}</p>
        @endif
    </div>
    <script>
        document.querySelector("button.go-back-btn").addEventListener("click", () => {
            window.location.href = window.history.back();
        })
    </script>
</x-root-layout>
