@vite(['resources/js/client/index.js', 'resources/js/user-management/index.js'])
@php
    $headers = ['Logo', 'Company Name', 'Business Type', 'TIN', 'Email', 'Phone', 'Status', 'Action'];
@endphp
<x-user-management title="Client Management" subtitle="Manage and access client records" buttonText="Add Company">
    @if (count($users) > 0)
        <x-table-management :headers=$headers>
            @foreach ($users as $key => $client)
                <tr data-row-number="{{ $key }}" class="striped">
                    <td id="td-item-img"><img class="item-img"
                            src="{{ asset('storage/profiles/' . $client->profile_img) }}" alt="Company Logo" />
                    </td>
                    <td>
                        <p>{{ $client->name }}</p>
                    </td>
                    <td>
                        <p>{{ $client->client_type }}</p>
                    </td>
                    <td>
                        <p>{{ $client->tin }}</p>
                    </td>
                    <td>
                        <p>{{ $client->email }}</p>
                    </td>
                    <td>
                        <p>{{ $client->phone_number }}</p>
                    </td>
                    <td class="{{ $client->suspended ? 'suspended' : 'active' }}">
                        <p>{{ $client->suspended ? 'Suspended' : 'Active' }}</p>
                    </td>
                    <td class="action-column">
                        <div>
                            <a title="Edit" href="{{ route('clients.edit', $client) }}">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('user.suspend', $client) }}" method="POST">
                                @csrf
                                <button title="{{ $client->suspended ? 'Unsuspend' : 'Suspend' }}" type="submit"
                                    style="background-color: transparent; border: none; outline: none;">
                                    <i class="fa-solid fa-ban" style="color: #ff0000; cursor: pointer"></i>
                                </button>
                            </form>
                            <form id="delete-form" action="{{ route('clients.destroy', $client) }}">
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
            <p>The generated password is: <strong>{{ session('password') }}</strong></p>
        @endif
        {{ $users->links() }}
    @else
        <h2 style="text-align: center;">No clients</h2>
    @endif
</x-user-management>

<dialog class="delete-item-dialog">
    <h3>Confirm Delete</h3>
    <form action="#" method="POST">
        @csrf
        @method('DELETE')
        <h4>Are you sure you want to delete this item?</h4>
        <p>This action is irreversible</p>
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
        <div class="buttons">
            <button type="submit">Add</button>
            <button id="close-dialog-btn" type="button">Cancel</button>
        </div>
    </div>
</x-dialog>
