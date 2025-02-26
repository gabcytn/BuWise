<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BuWise</title>
</head>
<body>
    <form method="POST" action="{{ route("clients.store") }}" enctype="multipart/form-data">
        @csrf
        <input name="name" type="text" placeholder="Name" value="{{ old("name") }}" required/>
        <input name="email" type="email" placeholder="Email" value="{{ old("email") }}" required/>
        <input name="phone_number" type="tel" placeholder="Phone Number" required value="{{ old("phone_number") }}" />
        <input name="tin" type="number" placeholder="TIN" required value="{{ old("tin") }}" />
        <input name="client_type" type="text" placeholder="Client Type" value="{{ old("client_type") }}" required>
        <input name="profile_img" type="file" placeholder="Profile Image" required/>
        <button type="submit">Submit</button>

        @if($errors->any())
            <p>{{ $errors->first() }}</p>
        @endif
    </form>
</body>
</html>
