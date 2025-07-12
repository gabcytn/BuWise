@vite(['resources/js/profile/partials.js', 'resources/css/profile/password-dialog.css'])

<form action="/logout" method="POST" id="logout-form">
    @csrf
</form>
<section class="profile-section">
    <div class="profile-section__wrapper">
        <h1>Privacy and Security</h1>
    </div>
    <hr />
    <div class="profile-section__wrapper privacy-section">
        <div class="privacy-row">
            <div class="privacy-row__text">
                <h3>Password</h3>
                <p>Update your password here. Ensure your account is using a long, random password to stay
                    secure.</p>
            </div>
            <div class="privacy-row__button">
                <button id="update-password">Update Password</button>
            </div>
        </div>
        <div class="privacy-row">
            <div class="privacy-row__text">
                <h3>Recycle Bin</h3>
                <p>View all your deleted invoices and journal entries.</p>
            </div>
            <form class="privacy-row__button" action="{{ route('bin') }}" style="display: block;">
                <button type="submit" id="bin">Bin</button>
            </form>
        </div>
        <div class="privacy-row">
            <div class="privacy-row__text">
                <h3>Logout</h3>
                <p>Logout of your session on this device.</p>
            </div>
            <div class="privacy-row__button">
                <button type="submit" form="logout-form" id="logout">Logout</button>
            </div>
        </div>
        <div class="privacy-row">
            <div class="privacy-row__text">
                <h3>Disable Two-Factor Authentication</h3>
                <p>Warning: If you lose the mobile device used for 2FA, temporarily disabling 2FA will also block login
                    access until you set up 2FA on a new phone.</p>
            </div>
            <div class="privacy-row__button">
                <button id="disable-two-factor">Disable Two-Factor</button>
            </div>
        </div>
        <div class="privacy-row">
            <div class="privacy-row__text">
                <h3>Delete Account</h3>
                <p>This action will permanently delete all user accounts and data associated with your organization.
                    This action cannot be undone.</p>
            </div>
            <div class="privacy-row__button">
                <button id="delete-account">Delete Account</button>
            </div>
        </div>
    </div>
</section>

<dialog class="password-dialog">
    <h3>Update Password</h3>
    <form action="/user/password" method="POST" id="password-form">
        @csrf
        @method('PUT')
        <!-- Current Password -->
        <div>
            <label for="update_password_current_password">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password" required
                autocomplete="current-password" />
        </div>
        <!-- New Password -->
        <div>
            <label for="update_password_password">New Password</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password" required />
        </div>
        <!-- Confirm Password -->
        <div>
            <label for="update_password_password_confirmation">Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" required
                autocomplete="new-password" />
        </div>
    </form>
    <hr />
    <div class="dialog-buttons">
        <button type="submit" form="password-form">Update Password</button>
        <input type="reset" form="password-form" value="Discard Changes" />
    </div>
</dialog>

<dialog class="mfa-dialog">
    <h3>Confirm Disabling of Two Factor Authentication</h3>
    <form action="/user/two-factor-authentication" method="POST" id="mfa-form">
        @csrf
        @method('DELETE')
        <div>
            <label for="disable">Type "disable" to disable 2FA</label>
            <input type="text" id="disable" />
        </div>
    </form>
    <hr />
    <div class="dialog-buttons">
        <button class="disabled" disabled type="submit" form="mfa-form">Disable Two-Factor</button>
        <input type="reset" form="mfa-form" value="Discard Changes" />
    </div>
</dialog>

<dialog class="delete-dialog">
    <h3>Confirm Deletion of Account</h3>
    <form action="{{ route('user.delete') }}" method="POST" id="delete-form">
        @csrf
        @method('DELETE')
        <div>
            <label for="delete">Type "delete" to delete your account</label>
            <input type="text" id="delete" />
        </div>
    </form>
    <hr />
    <div class="dialog-buttons">
        <button class="disabled" disabled type="submit" form="delete-form">Delete Account</button>
        <input type="reset" form="delete-form" value="Discard Changes" />
    </div>
</dialog>
