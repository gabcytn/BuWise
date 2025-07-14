@vite(['resources/js/staff/index.js'])
<x-user-management title="Staff Management" subtitle="Manage your bookkeeping staff" buttonText="Add Staff">
    @php
        $headers = ['Profile', 'First Name', 'Last Name', 'Type', 'Email', 'Status', 'Action'];
    @endphp
    @if (count($users) > 0)
        <x-table-management :headers=$headers>
            @foreach ($users as $staff)
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
                    <td class="{{ $staff->suspended ? 'suspended' : 'active' }}">
                        <p>{{ $staff->suspended ? 'Suspended' : 'Active' }}</p>
                    </td>
                    <td class="action-column">
                        <div>
                            <a title="Edit" href="{{ route('staff.edit', $staff) }}">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('user.suspend', $staff) }}" method="POST">
                                @csrf
                                <button title="{{ $staff->suspended ? 'Unsuspend' : 'Suspend' }}" type="submit"
                                    style="background-color: transparent; border: none; outline: none;">
                                    <i class="fa-solid fa-ban" style="color: #ff0000; cursor: pointer"></i>
                                </button>
                            </form>
                            <form id="delete-form" action="{{ route('staff.destroy', $staff) }}">
                                <button title="Delete" type="submit"
                                    style="background-color: transparent; border: none; outline: none;">
                                    <i class="fa-regular fa-trash-can" style="color: #ff0000; cursor: pointer"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table-management>
        @if (session('password'))
            <p style="font-size: 0.85rem;">The user default password is: <strong>{{ session('password') }}</strong></p>
            <p style="font-size: 0.7rem; color: #CA3A3A">Warning: you will only see this once, but you may change it in
                your settings.</p>
        @endif
        {{ $users->links() }}
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
            <select id="staff-type" name="staff_type" required>
                <option value="" selected disabled>Choose Role</option>
                <option value="2">Liaison Officer</option>
                <option value="3">Clerk</option>
            </select>
        </div>
        <div class="buttons">
            <button type="submit">Add</button>
            <button id="close-dialog-btn" type="button">Cancel</button>
        </div>
    </div>
</x-dialog>
