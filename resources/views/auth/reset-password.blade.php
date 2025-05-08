<x-root-layout>
    @vite(['resources/css/auth/reset-password.css'])
    <form method="POST" action="/reset-password" id="reset-password-form">
        @csrf

        <h2 id="page-title">Reset Password</h2>

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email">Email</label>
            <input id="email" name="email" value="{{ old('email', $request->email) }}" required autofocus />
            @error('email')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required />
            @error('password')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required />
            @error('password_confirmation')
                <p>{{ $message }}</p>
            @enderror
        </div>
        <div>
            <button type="submit">Reset Password</button>
        </div>
    </form>
</x-root-layout>
