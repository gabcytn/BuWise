@vite(['resources/css/dialog/dialog.css', 'resources/css/components/header.css', 'resources/css/components/notifs.css'])

@props(['title'])
<div class="header-container">
    <header class="header-sm">
        <i class="fa-solid fa-bars"></i>
        <h1 id="route-title">{{ $title }}</h1>
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
            <a id="conversation-route" href="/conversations"><i class="fa-solid fa-message"></i></a>
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


<script>
    document.getElementById('notifToggle').addEventListener('click', () => {
        document.getElementById('notificationPanel').classList.toggle('d-none');
    });
</script>
