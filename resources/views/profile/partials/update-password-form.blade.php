<section class="profile-section">
    <header>
        <h2>Update Password</h2>
        <p>Ensure your account is using a long, random password to stay secure.</p>
    </header>

    <form method="post" action="/user/password">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div>
            <label for="update_password_current_password">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password"
                autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" />
        </div>

        <!-- New Password -->
        <div>
            <label for="update_password_password">New Password</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="update_password_password_confirmation">Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" />
        </div>

        <div>
            <button type="submit" style="background-color: var(--green);">Save</button>
            @if (session('status') === 'password-updated')
                <p>Saved.</p>
            @endif
        </div>
    </form>
</section>
