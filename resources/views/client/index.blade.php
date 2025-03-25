@vite('resources/js/client/index.js')
<x-user-management title="Client Management" subtitle="Manage and access client records" buttonText="Add Company">
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
                <td><img class="item-img" src="{{ asset('storage/profiles/' . $client->profile_img) }}" alt="Company Logo" /></td>
                <td><p>{{ $client->name }}</p></td>
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
        <h2 style="text-align: center;">No clients</h2>
    @endif
</x-user-management>

<dialog id="add-company-dialog">
    <h2>Add Company</h2>
    <form id="add-company-form" method="POST" action="{{ route("clients.store") }}" enctype="multipart/form-data">
        @csrf
        <div class="form-img">
            <input name="profile_img" type="file" placeholder="Profile Image" required />
        </div>
        <div class="form-details">
            <div class="input-box">
                <label for="name">Company Name</label>
                <input id="name" name="name" type="text" placeholder="Name" value="{{ old("name") }}" required />
            </div>
            <div class="input-box">
                <label for="tin">Taxpayer Identification Number (TIN)</label>
                <input id="tin" name="tin" type="number" placeholder="123-456-789" required value="{{ old("tin") }}" />
                <p style="text-align: right; font-size: 0.6rem">Tip: Must be 9 characters, each three separated with a dash</p>
            </div>
            <div class="input-box">
                <label for="email">Email</label>
                <input name="email" type="email" placeholder="companymail@domain.com" value="{{ old("email") }}" required />
            </div>
            <div class="d-flex">
                <div class="input-box">
                    <label for="client_type">Business Type</label>
                    <input id="client_type" name="client_type" type="text" placeholder="Services" value="{{ old("client_type") }}" required />
                </div>
                <div class="input-box">
                    <label for="phone_number">Phone</label>
                    <input id="phone_number" name="phone_number" type="tel" placeholder="09" required value="{{ old("phone_number") }}" />
                </div>
            </div>
            <div class="input-box">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required />
            </div>
            <div class="buttons">
                <button type="submit">Add</button>
                <button id="close-dialog-btn" type="button">Cancel</button>
            </div>
            @if($errors->any())
                <p>{{ $errors->first() }}</p>
            @endif
        </div>
    </form>
</dialog>
