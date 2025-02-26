<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BuWise</title>
    <style>
        .company-logo {
            width: 100px;
            height: 100px;
        }
    </style>
</head>
<body>
    <a href="{{ route("clients.create") }}">Add Company</a>
    @if(count($clients) > 0)
    <table>
        <thead>
            <tr>
                <th>Logo</th>
                <th>Company Name</th>
                <th>TIN</th>
                <th>Business Type</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($clients as $client)
            <tr>
                <td><img class="company-logo" src="{{ asset("storage/profiles/" . $client->profile_img) }}" alt="Company Logo" />
                <td><p>{{ $client->name }}</p>
                <td><p>{{ $client->tin }}</p></td>
                <td><p>{{ $client->client_type }}</p></td>
                <td><p>{{ $client->email }}</p></td>
                <td><p>{{ $client->phone_number }}</p></td>
                <td class="action-column">
                    <a href="{{ route('clients.edit', $client) }}">Edit</a>
                    <form method="post" action="{{ route("clients.destroy", $client) }}">
                        @method("DELETE")
                        @csrf
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @else
        <h2>No clients</h2>
    @endif
</body>
</html>
