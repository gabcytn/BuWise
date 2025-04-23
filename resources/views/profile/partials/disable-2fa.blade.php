<section class="profile-section">
    <header>
        <h2>Disable 2FA</h2>
        <p id="warning">Warning: If you lose the mobile device used for 2FA, temporarily disabling 2FA will also
            block login access
            until you set up 2FA on a new phone.</p>
        <form action="/user/two-factor-authentication" method="POST">
            @csrf
            @method('DELETE')
            <div>
                <button type="submit">Disable</button>
            </div>
        </form>
    </header>
</section>
