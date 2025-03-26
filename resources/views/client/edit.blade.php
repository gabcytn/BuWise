<x-app-layout>
    @vite('resources/css/client/edit.css')
    <form action="{{ route('clients.update', $client) }}" method="post" enctype="multipart/form-data">
        @method("PUT")
        @csrf
        <label for="profile_img">Profile Image<label>
        <input id="profile_img" name="profile_img" type="file" placeholder="Profile Image" />
        <label for="name">Name<label>
        <input id="name" name="name" type="text" placeholder="Name" value="{{ $client->name }}"/>
        <label for="email">Email<label>
        <input id="email" name="email" type="email" placeholder="Email" value="{{ $client->email }}"/>
        <label for="phone_number">Phone Number<label>
        <input id="phone_number" name="phone_number" type="tel" placeholder="Phone Number" value="{{ $client->phone_number }}"/>
        <label for="tin">TIN<label>
        <input id="tin" name="tin" type="text" placeholder="TIN" value="{{ $client->tin }}"/>
        <label for="client_type">Client Type<label>
        <input id="client_type" name="client_type" type="text" placeholder="Client Type" value="{{ $client->client_type }}" >
        <label for="password">Password<label>
        <input id="password" type="password" name="password" placeholder="Password" />
        <button type="submit">Submit</button>

        @if($errors->any())
            <p>{{ $errors->first() }}</p>
        @endif
    </form>
</x-app-layout>
