<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BuWise</title>
    <style>
        .staff-profile {
            width: 100px;
            height: 100px;
        }
        #add-staff-dialog {
            border: none;
        }
        #add-staff-dialog::backdrop {
            background-color: rgba(0, 0, 0, 0.4);
        }
    </style>
</head>
<body>
    <button id="add-staff-btn">Add Staff</button>
    @if(count($staffs) > 0)
    <table>
        <thead>
            <tr>
                <td>Profile</td>
                <td>First Name</td>
                <td>Last Name</td>
                <td>Type</td>
                <td>Email</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
        @foreach($staffs as $staff)
            <?php
                $firstName = $staff->name;
                list($firstName, $lastName) = explode(' ', $staff->name, 2);
            ?>
            <tr>
                <td>
                    <img class="staff-profile" src="{{ asset("storage/profiles/" . $staff->profile_img) }}"  alt="Staff Profile Picture"/>
                </td>
                <td>{{ $firstName }}</td>
                <td>{{ $lastName }}</td>
                <td>{{ $staff->role->name }}</td>
                <td>{{ $staff->email }}</td>
                <td>
                    <a href="{{ route("staff.edit", $staff) }}">Edit</a>
                    <form action="{{ route("staff.destroy", $staff) }}" method="POST">
                        @csrf
                        @method("DELETE")
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @else
        <p>No staff</p>
    @endif

    <dialog id="add-staff-dialog">
        <form action="{{ route("staff.store") }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="profile">
                <label for="profile-img">Upload Profile Picture</label>
                <input id="profile-img" type="file" name="profile_img" required />
            </div>
            <label for="first-name">First Name</label>
            <input id="first-name" name="first_name" type="text" required value="{{ old("first_name") }}">
            <label for="last-name">Last Name</label>
            <input id="last-name" name="last_name" type="text" required value="{{ old("last_name") }}">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" required value="{{ old("email") }}">
            <label for="staff-type>">Staff Type</label>
            <select id="staff-type" name="staff_type">
                <option selected disabled>Choose Role</option>
                <option value="2">Liaison Officer</option>
                <option value="3">Clerk</option>
            </select>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required />
            <button type="submit">Submit</button>
            <button id="close-dialog-btn" type="button">Close</button>
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
    </dialog>

    <script>
        const addStaffDialog = document.querySelector("#add-staff-dialog");

        document.querySelector("#add-staff-btn").addEventListener("click", () => {
            addStaffDialog.showModal();
        });

        document.querySelector("#close-dialog-btn").addEventListener("click", () => {
            addStaffDialog.close();
        })
    </script>
</body>
</html>
