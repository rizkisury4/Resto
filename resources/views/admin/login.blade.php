<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>
    @if($errors->any())
        <div style="color:red">{{ $errors->first() }}</div>
    @endif
    <form method="post" action="{{ route('admin.login.post') }}">
        @csrf
        <div>
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required />
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" required />
        </div>
        <div>
            <button type="submit">Login</button>
        </div>
    </form>
</body>
</html>
