<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
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

<!-- ヘッダー -->
<header class="header">
  <a href="{{route('home.top')}}" class="header-left">
    <img src="{{asset('img/logo.png')}}" alt="Website's logo">
  </a>
  <div class="header-right btn">
    <a href="{{route('home.login')}}" class="header-right-link">
      <i class="fas fa-sign-in-alt"></i>
      ログイン
    </a>
  </div>
</header>

<!-- メインコンテンツ -->
<main>
@yield('content')
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
