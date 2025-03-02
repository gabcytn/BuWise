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
        #add-company-dialog {
            border: none;
        }
        #add-company-dialog::backdrop {
            background-color: rgba(0, 0, 0, 0.4);
        }
    </style>
</head>
<body>
    <button id="add-company-btn">Add Company</button>
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

    <dialog id="add-company-dialog">
        <form id="add-company-form" method="POST" action="{{ route("clients.store") }}" enctype="multipart/form-data">
            @csrf
            <input name="name" type="text" placeholder="Name" value="{{ old("name") }}" required />
            <input name="email" type="email" placeholder="Email" value="{{ old("email") }}" required />
            <input name="phone_number" type="tel" placeholder="Phone Number" required value="{{ old("phone_number") }}" />
            <input name="tin" type="number" placeholder="TIN" required value="{{ old("tin") }}" />
            <input name="client_type" type="text" placeholder="Client Type" value="{{ old("client_type") }}" required />
            <input name="profile_img" type="file" placeholder="Profile Image" required />
            <button type="submit">Submit</button>
            <button id="close-dialog-btn" type="button">Close</button>
            @if($errors->any())
                <p>{{ $errors->first() }}</p>
            @endif
        </form>
    </dialog>

    <script>
        const addCompanyDialog = document.querySelector("#add-company-dialog");
        document.querySelector("#add-company-btn").addEventListener("click", () => {
            addCompanyDialog.showModal();
        })

        document.querySelector("#close-dialog-btn").addEventListener("click", () => {
            addCompanyDialog.close();
        })
    </script>
</body>
</html>
