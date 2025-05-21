<section class="profile-section">
    <header>
        <h2>Profile Information</h2>
        <p>Update your account's profile information.</p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" enctype='multipart/form-data'>
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
            <label for="profile_img">Profile Picture</label>
            <input id="profile_img" name="profile_img" type="file"
                style="background-color: var(--clear-white); border: 1px solid var(--grey);" />
            <x-input-error :messages="$errors->get('profile_img')" />
        </div>

        <div>
            <button type="submit" style="background-color: var(--green);">Save</button>
            @if (session('status') === 'profile-updated')
                <p>Saved.</p>
            @endif
        </div>
    </form>
</section>
