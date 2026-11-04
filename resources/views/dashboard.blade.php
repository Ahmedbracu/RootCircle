<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RootCircle | Dashboard</title>
</head>
<body>
    <main>
        <h1>RootCircle</h1>
        <h2>Dashboard</h2>
        <p>Welcome, {{ auth()->user()->name }}.</p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Log out</button>
        </form>
    </main>
</body>
</html>
