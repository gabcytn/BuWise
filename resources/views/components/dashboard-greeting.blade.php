@php
    $user = request()->user();
@endphp
@vite(['resources/css/components/dashboard-greeting.css'])
<div class="profile-section">
    <div class="profile-img-wrapper">
        <img id="profile-img" src="{{ asset('storage/profiles/' . $user->profile_img) }}" alt="Profile Image" />
    </div>
    <div class="profile-info">
        <h2 class="dashboard-title">
            Welcome,&nbsp;{{ $user->name }}!
        </h2>
        <p class="dashboard-role">{{ $user->role->name }} of {{ $user->organization->name }}</p>
    </div>
</div>
