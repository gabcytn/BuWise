@vite(['resources/js/client/index.js', 'resources/js/user-management/index.js'])
<x-user-management title="Client Management" subtitle="Manage and access client records" buttonText="Add Company">
    @if (count($clients) > 0)
        <div class="table-wrapper">
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
                    @foreach ($clients as $client)
                        <tr>
                            <td id="td-item-img"><img class="item-img"
                                    src="{{ asset('storage/profiles/' . $client->profile_img) }}" alt="Company Logo" />
                            </td>
                            <td>
                                <p>{{ $client->name }}</p>
                            </td>
                            <td>
                                <p>{{ $client->tin }}</p>
                            </td>
                            <td>
                                <p>{{ $client->client_type }}</p>
                            </td>
                            <td>
                                <p>{{ $client->email }}</p>
                            </td>
                            <td>
                                <p>{{ $client->phone_number }}</p>
                            </td>
                            <td class="action-column">
                                <div>
                                    <a href="{{ route('clients.edit', $client) }}">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('clients.destroy', $client) }}">
                                        <button type="submit"
                                            style="background-color: transparent; border: none; outline: none;">
                                            <i class="fa-regular fa-trash-can"
                                                style="color: #ff0000; cursor: pointer"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $clients->links() }}
    @else
        <h2 style="text-align: center;">No clients</h2>
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

<x-dialog id="add-company-dialog" title="Add Company" formId="add-company-form" route="clients.store">
    <div class="form-img">
        <input name="profile_img" type="file" placeholder="Profile Image" required />
    </div>
    <div class="form-details">
        <div class="input-box">
            <label for="name">Company Name</label>
            <input id="name" name="name" type="text" placeholder="Name" value="{{ old('name') }}"
                required />
        </div>
        <div class="input-box">
            <label for="tin">Taxpayer Identification Number (TIN)</label>
            <input id="tin" name="tin" type="text" placeholder="123-456-789" required
                value="{{ old('tin') }}" />
            <p style="text-align: right; font-size: 0.6rem">Tip: Must be 9 characters, each three separated with a dash
            </p>
        </div>
        <div class="input-box">
            <label for="email">Email</label>
            <input name="email" type="email" placeholder="companymail@domain.com" value="{{ old('email') }}"
                required />
        </div>
        <div class="d-flex">
            <div class="input-box">
                <label for="client_type">Business Type</label>
                <input id="client_type" name="client_type" type="text" placeholder="Services"
                    value="{{ old('client_type') }}" required />
            </div>
            <div class="input-box">
                <label for="phone_number">Phone</label>
                <input id="phone_number" name="phone_number" type="tel" placeholder="09" required
                    value="{{ old('phone_number') }}" />
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
        @if ($errors->any())
            <p>{{ $errors->first() }}</p>
        @endif
    </div>
</x-dialog>
