<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtech フリマアプリ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css')}}" />
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <a href="/" class="header__logo">
                <img src="{{ asset('storage/images/logo.svg') }}" alt="coachtech">
            </a>
            <form class="form" action="/search" method="get">
                <div class="search-form">
                    <input type="text" name="keyword" placeholder="なにをお探しですか？">
                </div>
            </form>
            <form class="form" action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="header-nav__button">ログアウト</button>
            </form>
            <form class="form" action="/mypage" method="get">
                <button class="header-nav__button">マイページ</button>
            </form>
            <form class="form" action="/sell" method="get">
                <button class="sell__button">出品</button>
            </form>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>