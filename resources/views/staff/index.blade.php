@vite(['resources/js/staff/index.js'])
<x-user-management title="Staff Management" subtitle="Manage your bookkeeping staff" buttonText="Add Staff">
    @php
        $headers = ['Profile', 'First Name', 'Last Name', 'Type', 'Email', 'Action'];
    @endphp
    @if (count($staffs) > 0)
        <x-table-management :headers=$headers>
            @foreach ($staffs as $staff)
                @php
                    $firstName = $staff->name;
                    [$firstName, $lastName] = explode(' ', $staff->name, 2);
                @endphp
                <tr>
                    <td id="td-item-img">
                        <img class="item-img" src="{{ asset('storage/profiles/' . $staff->profile_img) }}"
                            alt="Staff Profile Picture" />
                    </td>
                    <td>{{ $firstName }}</td>
                    <td>{{ $lastName }}</td>
                    <td>{{ $staff->role->name }}</td>
                    <td>{{ $staff->email }}</td>
                    <td class="action-column">
                        <div>
                            <a href="{{ route('staff.edit', $staff) }}">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('staff.destroy', $staff) }}">
                                <button type="submit"
                                    style="background-color: transparent; border: none; outline: none;">
                                    <i class="fa-regular fa-trash-can" style="color: #ff0000; cursor: pointer"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table-management>
        {{ $staffs->links() }}
    @else
        <h2 style="text-align: center;">No staff</h2>
    @endif
</x-user-management>

<dialog class="delete-item-dialog">
    <h3 style="text-align: center; margin: 1rem 0;">Confirm Delete</h3>
    <form action="#" method="POST">
        @csrf
        @method('DELETE')
        <button style="margin-right: 0.25rem;" type="submit">Delete</button>
        <button style="margin-left: 0.25rem;" type="button">Cancel</button>
    </form>
</dialog>

<x-dialog id="add-staff-dialog" title="Add Staff" formId="add-staff-form" route="staff.store">
    <div class="form-img">
        <input type="file" name="profile_img" required />
    </div>
    <div class="form-details">
        <div class="input-box">
            <label for="first-name">First Name</label>
            <input id="first-name" name="first_name" type="text" required value="{{ old('first_name') }}">
        </div>
        <div class="input-box">
            <label for="last-name">Last Name</label>
            <input id="last-name" name="last_name" type="text" required value="{{ old('last_name') }}">
        </div>
        <div class="input-box">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" required value="{{ old('email') }}">
        </div>
        <div class="input-box">
            <label for="staff-type>">Staff Type</label>
            <select id="staff-type" name="staff_type">
                <option selected disabled>Choose Role</option>
                <option value="2">Liaison Officer</option>
                <option value="3">Clerk</option>
            </select>
        </div>
        <div class="input-box">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required />
        </div>
        <div class="buttons">
            <button type="submit">Add</button>
            <button id="close-dialog-btn" type="button">Cancel</button>
        </div>
        @if ($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</x-dialog>
