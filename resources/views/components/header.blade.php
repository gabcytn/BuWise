@vite(['resources/css/dialog/dialog.css', 'resources/css/components/header.css', 'resources/css/components/notifs.css', 'resources/js/echo.js'])

<div class="header-container">
    <header class="header-sm">
        <i class="fa-solid fa-bars"></i>
        <div class="header-side">
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
                    <p id="account-role">{{ ucfirst(request()->user()->role->name) }}</p>
                </div>
                <i class="fa-solid fa-circle-chevron-down"></i>
                <div class="popover d-none">
                    <ul>
                        <li id="profile">Profile</li>
                        <li id="logout">Logout</li>
                    </ul>
                </div>
            </div>
            <i class="fa-solid fa-bell" id="notifToggle"></i>
        </div>
    </header>
</div>

<!-- Notification Panel -->
<div class="notifications-panel d-none" id="notificationPanel">
    <div class="notifications-header">
        <span>Notifications</span>
    </div>
    <div class="notifications-list" id="notifList">
        <div class="notification-item">
            <p><strong>New Message:</strong> Your profile has been updated successfully.</p>
            <small>Just now</small>
        </div>
    </div>
</div>

<dialog class="confirm-logout-dialog">
    <h3 style="text-align: center; margin: 1rem 0;">Confirm Logout</h3>
    <form action="/logout" method="POST">
        @csrf
        <button style="margin-right: 0.25rem;" type="submit">Logout</button>
        <button style="margin-left: 0.25rem;" type="button">Cancel</button>
    </form>
</dialog>

<script>
    document.getElementById('notifToggle').addEventListener('click', () => {
        document.getElementById('notificationPanel').classList.toggle('d-none');
    });

    function toggleDropdown() {
        const dropdown = document.getElementById('notifDropdown');
        dropdown.classList.toggle('active');
    }
</script>
