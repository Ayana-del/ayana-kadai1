<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionablyLate</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <div class="header-utilities">
                <a class="header__logo" href="/">
                    FashionablyLate
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

                            @if ($currentRoute === 'register')
                            <form action="{{ route('login') }}" method="GET">
                                <button class="header-nav__button">Login</button>
                            </form>

                            @elseif ($currentRoute === 'login')
                            <form action="{{ route('register') }}" method="GET">
                                <button class="header-nav__button">Register</button>
                            </form>

                            @elseif (!in_array($currentRoute, ['contact.index', 'contact.confirm', 'contact.thanks']))
                            <form action="{{ route('login') }}" method="GET">
                                <button class="header-nav__button">Login</button>
                            </form>
                            @endif

                            @endguest
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>