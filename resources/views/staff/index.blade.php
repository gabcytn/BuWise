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
            <tr>
                <td>
                    <img class="staff-profile" src="{{ asset("storage/profiles/" . $staff->profile_img) }}"  alt="Staff Profile Picture"/>
                </td>
                <td>{{ $staff->name }}</td>
                <td>{{ $staff->name }}</td>
                <td>{{ $staff->role->name }}</td>
                <td>{{ $staff->email }}</td>
                <td>
                    <a href="#">Edit</a>
                    <a href="#">Delete</a>
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
