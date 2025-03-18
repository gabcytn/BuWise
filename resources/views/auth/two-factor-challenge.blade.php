<!DOCTYPE html>
<html>
<head>
    <title>Laravel 2FA Starter</title>
</head>
<body>
    <form action="/two-factor-challenge" method="POST">
        @csrf
        <input name="code" required />
        <button type="submit">Submit</button>
    </form>
</body>
</html>
