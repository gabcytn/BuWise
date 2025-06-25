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
    <div class="notifications-list" id="notifList"></div>
</div>

<div class="d-none notification-banner" id="banner">
    <div class="banner-content">
        <p>You have received a notification.</p>
        <i class="fa-solid fa-xmark"></i>
    </div>
</div>

<script>
    document.getElementById('notifToggle').addEventListener('click', () => {
        document.getElementById('notificationPanel').classList.toggle('d-none');
    });

    document.querySelector("#banner .fa-xmark").addEventListener("click", (e) => {
        document.querySelector("#banner").classList.add("d-none");
    });
</script>
