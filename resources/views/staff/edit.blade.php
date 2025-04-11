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
        <label for="staff-type>">Staff Type</label>
        <select id="staff-type" name="staff_type" required>
            <option selected disabled>Choose Role</option>
            <option value="2">Liaison Officer</option>
            <option value="3">Clerk</option>
        </select>
        <label for="password">Password</label>
        <input id="password" name="password" type="password" required />
        <button type="submit">Submit</button>
        <a href="{{ url()->previous() }}">Cancel</a>
        @if ($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </form>
</x-app-layout>
