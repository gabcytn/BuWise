@vite('resources/css/dialog/dialog.css')
<div class="header-container">
    <header class="header-sm">
        <i class="fa-solid fa-bars"></i>
        <div class="header-side">
            <i class="fa-solid fa-bell"></i>
            <div class="header-side__account" style="cursor: pointer;">
                @php
                    $profileImg = request()->user()->profile_img;
                    if ($profileImg) {
                        $url = asset('storage/profiles/' . $profileImg);
                    } else {
                        $url = 'https://placehold.co/40';
                    }
                @endphp
                <img src="{{ $url }}" alt="Profile Image" width="40" height="40" />
                <div class="header-side__account--details">
                    <p id="account-name">{{ request()->user()->name }}</p>
                    <p id="account-role">{{ request()->user()->role->name }}</p>
                </div>
                <i class="fa-solid fa-circle-chevron-down"></i>
                <div class="popover d-none">
                    <ul>
                        <li id="profile">Profile</li>
                        <li id="logout">Logout</li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
</div>
<dialog class="confirm-logout-dialog">
    <h3 style="text-align: center; margin: 1rem 0;">Confirm Logout</h3>
    <form action="/logout" method="POST">
        @csrf
        <button style="margin-right: 0.25rem;" type="submit">Logout</button>
        <button style="margin-left: 0.25rem;" type="button">Cancel</button>
    </form>
</dialog>
