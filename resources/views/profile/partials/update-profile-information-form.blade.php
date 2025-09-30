@vite(['resources/css/profile/partials/update-profile-info.css'])
<section class="profile-section">
    <div class="profile-section__wrapper">
        <div class="profile-section__header">
            <h2>Account Details</h2>
        </div>
    </div>
    <hr />
    @php
        $profileImg = $user->profile_img;
        if ($profileImg) {
            $url = asset('storage/profiles/' . $profileImg);
        } else {
            $url = 'https://placehold.co/40';
        }
    @endphp
    <div class="profile-section__wrapper">
        <form id="profile-form" method="post" action="{{ route('profile.update') }}" enctype='multipart/form-data'>
            @csrf
            @method('PUT')
            <div class="file-row">
                <div>
                    <img src="{{ $url }}" alt="User Profile Picture" />
                </div>
                <div>
                    <input id="profile_img" name="profile_img" type="file" />
                    <p for="profile_img"><i class="fa-solid fa-circle-info"></i>File must be a "jpg", "png", or
                        "svg", and less than 5mb</p>
                </div>
            </div>
            <div class="details-row">
                <div class="details-box">
                    <label for="name">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                        required />
                </div>
                <div class="details-box">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}"
                        required />
                </div>
                <div class="details-box">
                    <label for="gender">Gender</label>
                    <select required name="gender" id="gender">
                        <option {{ !$user->gender ? 'selected' : '' }} value="" disabled>Choose a gender</option>
                        <option {{ $user->gender === 'm' ? 'selected' : '' }} value="m">Male</option>
                        <option {{ $user->gender === 'f' ? 'selected' : '' }} value="f">Female</option>
                        <option {{ $user->gender === 'n' ? 'selected' : '' }} value="n">Prefer not to say</option>
                    </select>
                </div>
                <div class="details-box">
                    <label for="organization-name">Organization Name</label>
                    <input id="organization-name" name="organization_name" type="text"
                        value="{{ old('organization_name', $user->organization->name) }}" required
                        {{ $user->role_id !== \App\Models\Role::ACCOUNTANT ? 'disabled' : '' }} />
                </div>
                <div class="details-box">
                    <label for="organization-address">Organization Address</label>
                    <input id="organization-address" name="organization_address" type="text"
                        value="{{ old('organization_address', $user->organization->address) }}" required
                        {{ $user->role_id !== \App\Models\Role::ACCOUNTANT ? 'disabled' : '' }} />
                </div>
            </div>

        </form>
    </div>
    <hr />
    <div class="profile-section__wrapper">
        <div class="profile-section__bottom">
            <button type="submit" form="profile-form">Save Changes</button>
            <input type="reset" form="profile-form" />
        </div>
    </div>
</section>
