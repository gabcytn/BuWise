<section class="profile-section">
    <header>
        <h2>Profile Information</h2>
        <p>Update your account's profile information and email address.</p>
    </header>

    <form method="post" action="/user/profile-information">
        @csrf
        @method('PUT')

        <div>
            <label for="name">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus
                autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <button type="submit">Save</button>
            @if (session('status') === 'profile-updated')
                <p>Saved.</p>
            @endif
        </div>
    </form>
</section>
