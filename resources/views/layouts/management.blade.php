<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title') | Twitter運用</title>

  <!-- FontAwesome -->
  <link href="https://use.fontawesome.com/releases/v5.0.11/css/all.css" rel="stylesheet">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

  <!-- Swiper -->
  <link rel="stylesheet" href="https://unpkg.com/swiper/css/swiper.min.css">

  <!-- CSS -->
  <link rel="stylesheet" href="{{asset('css/reset.css')}}">
  <link rel="stylesheet" href="{{asset('css/style.min.css')}}">

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.0.js" integrity="sha256-r/AaFHrszJtwpe+tHyNi/XCfMxYpbsRg2Uqn0x3s2zc=" crossorigin="anonymous"></script>
</head>
<body>
<div class="whole js-whole"></div>

<!-- フラッシュメッセージ -->
@if (Session::has('message'))
  <p class="flash_message">{{ session('message') }}</p>
@endif

<!-- ヘッダー -->
<header class="header header-management">
  <div class="toggle js-click-toggle">
    <i class="fas fa-bars js-click-toggle-icon-t active"></i>
    <i class="fas fa-times js-click-toggle-icon-f"></i>
  </div>
  <a href="{{route('management.dashboard', ['id' => $user->twitter_id])}}" class="header-left">
    <img src="{{asset('img/logo.png')}}" alt="Website's logo">
  </a>
</header>

<main>
<div class="dashboard">

  <!-- サイドバー -->
  <div class="sidebar js-click-toggle-sidebar">

    <div class="sidebar-profile">
      <div class="sidebar-profile-user">
        <img src="{{ $user->img }}" alt="User profile image" class="user-img">
        <div>
          <p class="sidebar-profile-user-name">{{ $user->name }}</p>
          <p class="sidebar-profile-user-id"><span>@</span>{{ $user->nickname }}</p>
        </div>
      </div>
      <div class="sidebar-profile-setting">
        <a href="{{route('management.reload', ['id' => $user->twitter_id, 'active_flg' => $active_flg])}}" class="sidebar-profile-setting-reload">
          <i class="fas fa-sync btn"></i>
        </a>
        <div class="sidebar-profile-setting-logout">
          <a href="{{route('auth.logout')}}">ログアウト</a>
        </div>
      </div>
    </div>

    <ul class="menus js-click-toggle-menu">
      <li class="menu @if ($active_flg === 1) active @endif">
        <a href="{{route('management.dashboard', ['id' => $user->twitter_id])}}">
          <i class="fas fa-chart-line"></i>
          ダッシュボード
        </a>
      </li>
      <li class="menu @if ($active_flg === 2) active @endif">
        <a href="{{route('management.follow', ['id' => $user->twitter_id])}}">
          <i class="fas fa-user-plus"></i>
          自動フォロー
        </a>
      </li>
      <li class="menu @if ($active_flg === 3) active @endif">
        <a href="{{route('management.followList', ['id' => $user->twitter_id, 'order' => 'follower', 'sort' => 'follow_big'])}}">
          <i class="fas fa-list"></i>
          フォローリスト
        </a>
      </li>
      <li class="menu @if ($active_flg === 4) active @endif">
        <a href="{{route('management.setting', ['id' => $user->twitter_id])}}">
          <i class="fas fa-id-card"></i>
          アカウント管理
        </a>
      </li>
    </ul>

  </div>

  <div class="main-content">
    <!-- メインコンテンツ -->
    @yield('content')
  </div>
</div>
</main>

<!-- フッター -->
<footer class="footer">
  <div class="footer-left"><a href="{{route('home.tokutei')}}">特定商取引法に基づく表記</a></div>
  <div class="footer-right">
    <p>Copyright &copy; </p><a href="http://0-1-llc.com/">&nbsp;ゼロワンLLC.&nbsp;</a><p> All Rights Reserved.</p></div>
</footer>

<!-- JS -->
<script src="https://unpkg.com/swiper/js/swiper.min.js"></script>
<script src="{{asset('js/main.js')}}"></script>

</body>
</html>
