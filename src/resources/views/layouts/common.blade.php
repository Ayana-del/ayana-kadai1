<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionablyLate</title>

    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('styles')
</head>

<body>
    @unless (Request::is('thanks'))
    <header class="header">
        <div class="header__inner">
            <div class="header-utilities">
                <a class="header__logo" href="/">
                    <h1>FashionablyLate</h1>
                </a>
            </div>
        </div>
    </header>
    @endunless

    <main class="container">
        @yield('content')
    </main>
</body>

</html>