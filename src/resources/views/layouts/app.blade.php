<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>COACHTECH</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
  <link rel="stylesheet" href="{{ asset('css/common.css') }}">
  <link rel="stylesheet" href="{{ asset('css/header.css') }}">
  @yield('css')
</head>

<body>
  <header class="header">
    <div class="header__inner">
      <div class="header__logo">
        <a href="/" class="header__logo-link">
          <img src="{{ asset('images/header-logo.png') }}" alt="COACHTECH" class="header__logo-img">
        </a>
      </div>

      <div class="header__search">
        <form action="/search" method="GET" class="header__search-form">
          <input type="text" name="keyword" class="header__search-input" placeholder="なにをお探しですか？">
        </form>
      </div>

      <nav class="header__nav">
        <ul class="header__nav-list">
          <li class="header__nav-item">
            <a href="/logout" class="header__nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>
            <form id="logout-form" action="/logout" method="POST" style="display: none">
              @csrf
            </form>
          </li>
          <li class="header__nav-item">
            <a href="/mypage" class="header__nav-link">マイページ</a>
          </li>
          <li class="header__nav-item">
            <a href="/sell" class="c-button c-button--header-sell">出品</a>
          </li>
        </ul>
      </nav>
    </div>
  </header>

  <main>
    @yield('content')
  </main>
</body>

</html>