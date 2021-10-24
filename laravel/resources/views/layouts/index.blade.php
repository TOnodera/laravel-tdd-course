<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ブログ</title>
</head>
<body>

    <nav>
        <li><a href="/">TOP（ブログ一覧）</a></li>
        @auth
        <li><a href="/mypage/blogs">マイブログ一覧</a></li>
        <li>
            <form action="/mypage/logout" method="post">
                @csrf
                <input type="submit" value="ログアウト">
            </form>
        </li>
        @else
            <li><a href="{{ route('login') }}">ログイン</a></li>
        @endauth
    </nav>

    @yield('content')
</body>
</html>