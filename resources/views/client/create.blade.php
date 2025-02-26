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
        <input name="name" type="text" placeholder="Name" required/>
        <input name="email" type="email" placeholder="Email" required/>
        <input name="phone_number" type="tel" placeholder="Phone Number" required />
        <input name="tin" type="number" placeholder="TIN" required />
        <input name="client_type" type="text" placeholder="Client Type" required>
        <input name="profile_img" type="file" placeholder="Profile Image" required/>
        <button type="submit">Submit</button>

        @if($errors->any())
            <p>{{ $errors->first() }}</p>
        @endif
    </form>
</body>
</html>
