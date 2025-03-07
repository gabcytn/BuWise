<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BuWise</title>
</head>
<body>
<div class="container">
    <form action="{{ route('clients.update', $client) }}" method="post" enctype="multipart/form-data">
        @method("PUT")
        @csrf
        <input name="profile_img" type="file" placeholder="Profile Image" />
        <input name="name" type="text" placeholder="Name" value="{{ $client->name }}"/>
        <input name="email" type="email" placeholder="Email" value="{{ $client->email }}"/>
        <input name="phone_number" type="tel" placeholder="Phone Number" value="{{ $client->phone_number }}"/>
        <input name="tin" type="number" placeholder="TIN" value="{{ $client->tin }}"/>
        <input name="client_type" type="text" placeholder="Client Type" value="{{ $client->client_type }}" >
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit">Submit</button>

        @if($errors->any())
            <p>{{ $errors->first() }}</p>
        @endif
    </form>
</div>
</body>
</html>
