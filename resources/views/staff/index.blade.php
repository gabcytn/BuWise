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
    </style>
</head>
<body>
    <a href="{{ route("staff.create") }}">Add Staff</a>
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
                        <button type="submit">Delete</a>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @else

        <p>No staff</p>
    @endif
</body>
</html>
