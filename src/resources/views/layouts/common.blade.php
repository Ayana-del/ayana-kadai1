<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionablyLate</title>

    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('styles')
</head>

<body>
    <header>
        <h1>FashionablyLate</h1>
    </header>

    <main class="container">
        @yield('content')
    </main>
</body>

</html>