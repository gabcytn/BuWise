<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h3 class="text-center">Dashboard</h3>
        <form action="/user/two-factor-authentication" method="POST">
            @csrf
            @if (auth()->user()->two_factor_secret)
                @method("DELETE")
                <button class="btn btn-danger my-3" type="submit">Disable 2FA</button>
            @else
                <button class="btn btn-primary my-3" type="submit">Enable 2FA</button>
            @endif
        </form>

        @if (session('status') === 'two-factor-authentication-enabled')
            <div class="mb-4 font-medium text-sm">
                Please finish configuring two factor authentication below.
            </div>
            {!! auth()->user()->twoFactorQrCodeSvg() !!} <br />
            <form action="/user/confirmed-two-factor-authentication" method="POST">
                @csrf
                <label class="form-label" for="code">Code</label>
                <input name="code" class="form-control" id="code"/>
                <button class="btn btn-primary" type="submit">Submit Code</button>
            </form>
        @endif


        @if (session('status') == 'two-factor-authentication-confirmed')
            <div class="mb-4 font-medium text-sm">
                Two factor authentication confirmed and enabled successfully.
            </div>
            @foreach ((array) auth()->user()->recoveryCodes() as $code)
                {{ $code }} <br />
            @endforeach
        @endif




        <form action="/logout" method="POST">
            @csrf
            <button class="btn btn-danger mt-3" type="submit">Log out</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
