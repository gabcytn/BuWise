@vite('resources/css/dialog/dialog.css')
@vite('resources/css/components/notifs.css')

<div class="header-container">
    <header class="header-sm">
        <i class="fa-solid fa-bars"></i>
        <div class="header-side">
            <i class="fa-solid fa-bell" id="notifToggle"></i>
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
        </div>
    </header>
</div>

<!-- Notification Panel -->
<div class="notifications-panel d-none" id="notificationPanel">
    <div class="notifications-header">
        <span>Notifications</span>
        <div class="notification-filter">
            <button class="dropdown-toggle" onclick="toggleDropdown()">All <i class="fa-solid fa-chevron-down"></i></button>
            <div class="dropdown-menu" id="notifDropdown">
                <button>All</button>
                <button>Unread</button>
                <button>Important</button>
                <button>New Client</button>
                <button>New Invoice</button>
                <button>New Entry</button>
                <button>New Login</button>
            </div>
        </div>
    </div>
    <div class="notifications-list" id="notifList">
        <div class="notification-item">
            <i class="fa-solid fa-user-check notification-icon"></i>
            <div class="notification-content">
                <div class="notification-title">New User Registered</div>
                <div class="notification-time">1 minute ago</div>
            </div>
            <span class="notification-close" onclick="this.parentElement.remove()">×</span>
        </div>
        <div class="notification-item">
            <i class="fa-solid fa-user-check notification-icon"></i>
            <div class="notification-content">
                <div class="notification-title">New User Registered</div>
                <div class="notification-time">1 minute ago</div>
            </div>
            <span class="notification-close" onclick="this.parentElement.remove()">×</span>
        </div>
        <div class="notification-item">
            <i class="fa-solid fa-user-plus notification-icon"></i>
            <div class="notification-content">
                <div class="notification-title">New Client Added</div>
                <div class="notification-time">2 minutes ago</div>
            </div>
            <span class="notification-close" onclick="this.parentElement.remove()">×</span>
        </div>
            
        <div class="notification-item">
            <i class="fa-solid fa-triangle-exclamation notification-icon"></i>
            <div class="notification-content">
                <div class="notification-title">Missing Invoice</div>
                <div class="notification-time">3 minutes ago</div>
            </div>
            <span class="notification-close" onclick="this.parentElement.remove()">×</span>
        </div>
        <div class="notification-item">
            <i class="fa-solid fa-envelope-open-text notification-icon"></i>
            <div class="notification-content">
                <div class="notification-title">Camille Garcia added a new contact</div>
                <div class="notification-time">10 minutes ago</div>
            </div>
            <span class="notification-close" onclick="this.parentElement.remove()">×</span>
        </div>
        <div class="notification-item">
            <i class="fa-solid fa-user-clock notification-icon"></i>
            <div class="notification-content">
                <div class="notification-title">Lailanie Joyeyo made a new Journal Entry</div>
                <div class="notification-time">30 minutes ago</div>
            </div>
            <span class="notification-close" onclick="this.parentElement.remove()">×</span>
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

<!-- Optional JavaScript to toggle notifications -->
<script>
    document.getElementById('notifToggle').addEventListener('click', () => {
        document.getElementById('notificationPanel').classList.toggle('d-none');
    });

    function toggleDropdown() {
        const dropdown = document.getElementById('notifDropdown');
        dropdown.classList.toggle('active');
    }

    window.addEventListener('click', function(e) {
        const dropdown = document.getElementById('notifDropdown');
        if (!e.target.closest('.notification-filter')) {
            dropdown.classList.remove('active');
        }
    });
</script>
