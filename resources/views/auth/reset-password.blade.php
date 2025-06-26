<x-root-layout>
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
    @vite(['resources/css/auth/reset-password.css'])

    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-wrapper">
            <img src="/images/buwiselogo.png" alt="BuWise Logo" class="logo">
        </div>
    </nav>

    <div class="form-container">
        <h2 class="title">Reset Password</h2>
        <p class="description">Enter your email and new password below.</p>

        <form method="POST" action="/reset-password" id="reset-password-form">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="form-group">
                <label>Email Address</label>
                <div class="input-wrapper">
                    <i class="mdi mdi-email-outline"></i>
                    <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" placeholder="name@domain.com" required autofocus />
                </div>
            </div>

            <div class="form-group">
                <label>New Password</label>
                <div class="input-wrapper">
                    <i class="mdi mdi-lock-outline"></i>
                    <input id="password" name="password" type="password" placeholder="Enter your password" required />
                    <i class="mdi mdi-eye-off-outline toggle-password" onclick="toggleVisibility('password', this)"></i>
                </div>
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <div class="input-wrapper">
                    <i class="mdi mdi-lock-outline"></i>
                    <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Enter your new password again" required />
                    <i class="mdi mdi-eye-off-outline toggle-password" onclick="toggleVisibility('password_confirmation', this)"></i>
                </div>
            </div>

            <button type="submit" class="primary-button">Reset Password</button>
            <button type="button" class="logout-button">Go Back</button>
        </form>
    </div>

    <script>
        function toggleVisibility(id, icon) {
            const input = document.getElementById(id);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('mdi-eye-off-outline', 'mdi-eye-outline');
            } else {
                input.type = 'password';
                icon.classList.replace('mdi-eye-outline', 'mdi-eye-off-outline');
            }
        }

        document.querySelector(".logout-button").addEventListener("click", () => {
            window.history.back();
        });
    </script>
</x-root-layout>
