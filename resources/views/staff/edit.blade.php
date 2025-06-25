@php
    [$firstName, $lastName] = explode(' ', $staff->name, 2);
@endphp
<x-app-layout>
    @vite('resources/css/client/edit.css')
    <form action="{{ route('staff.update', $staff) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="profile">
            <label for="profile-img">Upload Profile Picture</label>
            <input id="profile-img" type="file" name="profile_img" />
        </div>
        <label for="first-name">First Name</label>
        <input id="first-name" name="first_name" type="text" required value="{{ $firstName }}">
        <label for="last-name">Last Name</label>
        <input id="last-name" name="last_name" type="text" required value="{{ $lastName }}">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" required value="{{ $staff->email }}">
        <label for="role">Staff Type</label><br />
        <select id="role" name="staff_type" required>
            <option disabled>Choose Role</option>
            <option value="2" {{ $staff->role_id === '2' ? 'selected' : '' }}>Liaison Officer</option>
            <option value="3" {{ $staff->role_id === '3' ? 'selected' : '' }}>Clerk</option>
        </select><br />
        <label for="password">Password</label>
        <input id="password" name="password" type="password" />
        <button type="submit">Submit</button>
    </form>
</x-app-layout>
