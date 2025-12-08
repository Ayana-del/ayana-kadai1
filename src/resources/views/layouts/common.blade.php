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
                <nav>
                    <ul class="header-nav">
                        <li class="header-nav__item">
                            @auth
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="header-nav__button">logout</button>
                            </form>
                            @endauth

                            @guest
                            @php
                            $currentRoute = Route::currentRouteName();
                            @endphp

                            @if ($currentRoute !== 'login' && $currentRoute !== 'contact.index')
                            <form action="{{ route('login') }}" method="GET">
                                <button class="header-nav__button">login</button>
                            </form>
                            @endif
                            @endguest
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    @endunless

    <main class="container">
        @yield('content')
    </main>
</body>

</html>