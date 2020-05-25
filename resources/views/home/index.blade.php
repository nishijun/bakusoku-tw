@extends('layouts.top')
@section('title', 'ホーム')
@section('content')

<!-- Topイメージ  PC版-->
<img src="{{asset('img/keyvisual.png')}}" alt="Key visual" class="top-image">
<!-- Topイメージ  SP版-->
<img src="{{asset('img/keyvisual-sp.png')}}" alt="Key visual" class="top-image-sp">

<div class="top-title position">
  <img src="{{asset('img/left-title.png')}}" alt="Left title">
  <p class="top-title-paragraph"><span class="top-title-paragraph-span">人気</span>プログラミングスクール<span class="top-title-paragraph-pc">、</span><br class="top-title-paragraph-sp"><span class="top-title-paragraph-span">ウェブカツ!!</span>の<span class="top-title-paragraph-span">例</span></p>
  <img src="{{asset('img/right-title.png')}}" alt="Right title">
</div>
<p class="top-sentence position">爆速のプロモーション効果</p>

<!-- Topイメージ2  PC版-->
<img src="{{asset('img/keyvisual2.png')}}" alt="Key visual2" class="top-image margin">
<!-- Topイメージ2  SP版-->
<img src="{{asset('img/keyvisual2-sp.png')}}" alt="Key visual2" class="top-image-sp">

<div class="top-title">
  <img src="{{asset('img/left-title.png')}}" alt="Left title">
  <p class="top-title-paragraph"><span class="top-title-paragraph-span">ツイッター集客</span>に<br class="top-title-paragraph-sp"><span class="top-title-paragraph-span">必要</span>な<span class="top-title-paragraph-span">機能</span>だけを<span class="top-title-paragraph-span">厳選</span></p>
  <img src="{{asset('img/right-title.png')}}" alt="Right title">
</div>
<p class="top-sentence"><span class="top-sentence-span">4</span>つの機能</p>

<!-- 機能一覧 -->
<div class="functions">

  <div class="function-bg1 function">
    <div class="container function-inner">
      <img src="{{asset('img/function-img1.png')}}" alt="Function image1" class="function-inner-image-pc">
      <img src="{{asset('img/function-img1-sp.png')}}" alt="Function image1" class="function-inner-image-sp">
      <div class="function-inner-content">
        <div class="function-inner-content-title">自動フォロー</div>
        <p class="function-inner-content-text">指定したツイッターアカウントのフォロワー全員を「自動フォロー」。<br>影響力のある類似アカウントなどを狙いましょう。類似アカウントのフォロワー達ならきっとあなたのアカウントもフォローしてくれる。効率よくフォロワー数を伸ばせます。</p>
      </div>
    </div>
  </div>

  <div class="function-bg2 function">
    <div class="container function-inner">
      <div class="function-inner-content">
        <div class="function-inner-content-title">キーワードフィルタ</div>
        <p class="function-inner-content-text">特定のキーワードを呟いている人のみを自動フォローの対象にする「キーワードフィルタ」。これを使えば、無駄がなくなりフォロワーの管理もラクに。また、フォローを返してくれたアカウントとも、より良い関係を築くことができるため、フォローの解除率は下がります。</p>
      </div>
      <img src="{{asset('img/function-img2.png')}}" alt="Function image2" class="function-inner-image-pc">
      <img src="{{asset('img/function-img2-sp.png')}}" alt="Function image2" class="function-inner-image-sp">
    </div>
  </div>

  <div class="function-bg1 function">
    <div class="container function-inner">
      <img src="{{asset('img/function-img3.png')}}" alt="Function image3" class="function-inner-image-pc">
      <img src="{{asset('img/function-img3-sp.png')}}" alt="Function image3" class="function-inner-image-sp">
      <div class="function-inner-content">
        <div class="function-inner-content-title">自動アンフォロー</div>
        <p class="function-inner-content-text">フォローを返してくれない、あるいはフォローを解除されたときの「自動アンフォロー機能」を完備。フォローされていないアカウントは一週間でこちらのフォローも解除されます。アカウント管理には一切の手間がありません。</p>
      </div>
    </div>
  </div>

  <div class="function-bg2 function">
    <div class="container function-inner">
      <div class="function-inner-content">
        <div class="function-inner-content-title">集客分析グラフ</div>
        <p class="function-inner-content-text">フォロワーやアンフォローの数を見える化する「集客分析グラフ」。データを見える化することで、ABテストの結果が簡単にわかります。あなたの施策が成功したか、それとも効果がなかったのか。どんどん分析し、目標をクリアしていきましょう。</p>
      </div>
      <img src="{{asset('img/function-img4.png')}}" alt="Function image4" class="function-inner-image-pc">
      <img src="{{asset('img/function-img4-sp.png')}}" alt="Function image4" class="function-inner-image-sp">
    </div>
  </div>

</div>

<div class="top-title">
  <img src="{{asset('img/left-title.png')}}" alt="Left title">
  <p class="top-title-paragraph"><span class="top-title-paragraph-span">お得</span>に<span class="top-title-paragraph-span">ご提供</span>いたします！</p>
  <img src="{{asset('img/right-title.png')}}" alt="Right title">
</div>
<p class="top-sentence">料金プラン</p>

<!-- 料金 -->
<div class="fares">

  <!-- PC版 -->
  <div class="fare green-border fare-pc">
    <p class="fare-title green-balloon green-stripe">無料プラン</p>
    <p class="fare-fee green-color"><span class="fare-fee-span">0</span>円</p>
    <ul class="fare-list">
      <li class="fare-list-item">内容について入ります</li>
      <li class="fare-list-item">内容について入ります</li>
      <li class="fare-list-item">内容について入ります</li>
      <li class="fare-list-item">内容について入ります</li>
      <li class="fare-list-item">内容について入ります</li>
      <li class="fare-list-item">内容について入ります</li>
    </ul>
    <div class="fare-button green-bg btn">
      <a class="fare-button-link" href="{{route('home.login')}}">申込む</a>
    </div>
  </div>

  <div class="fare blue-border fare-pc">
    <p class="fare-title blue-balloon blue-stripe">無料プラン</p>
    <p class="fare-fee blue-color"><span class="fare-fee-span">0</span>円</p>
    <ul class="fare-list">
      <li class="fare-list-item">内容について入ります</li>
      <li class="fare-list-item">内容について入ります</li>
      <li class="fare-list-item">内容について入ります</li>
      <li class="fare-list-item">内容について入ります</li>
      <li class="fare-list-item">内容について入ります</li>
      <li class="fare-list-item">内容について入ります</li>
    </ul>
    <div class="fare-button blue-bg btn">
      <a class="fare-button-link" href="{{route('home.login')}}">申込む</a>
    </div>
  </div>

  <div class="fare red-border fare-pc">
    <p class="fare-title red-balloon red-stripe">無料プラン</p>
    <p class="fare-fee red-color"><span class="fare-fee-span">0</span>円</p>
    <ul class="fare-list">
      <li class="fare-list-item">内容について入ります</li>
      <li class="fare-list-item">内容について入ります</li>
      <li class="fare-list-item">内容について入ります</li>
      <li class="fare-list-item">内容について入ります</li>
      <li class="fare-list-item">内容について入ります</li>
      <li class="fare-list-item">内容について入ります</li>
    </ul>
    <div class="fare-button red-bg btn">
      <a class="fare-button-link" href="{{route('home.login')}}">申込む</a>
    </div>
  </div>

  <!-- SP版 -->
  <div class="swiper-container swiper-top fare-sp">
    <div class="swiper-wrapper">

      <div class="swiper-slide">
        <div class="fare green-border">
          <p class="fare-title green-balloon green-stripe">無料プラン</p>
          <p class="fare-fee green-color"><span class="fare-fee-span">0</span>円</p>
          <ul class="fare-list">
            <li class="fare-list-item">内容について入ります</li>
            <li class="fare-list-item">内容について入ります</li>
            <li class="fare-list-item">内容について入ります</li>
            <li class="fare-list-item">内容について入ります</li>
            <li class="fare-list-item">内容について入ります</li>
            <li class="fare-list-item">内容について入ります</li>
          </ul>
          <div class="fare-button green-bg btn">
            <a href="fare-button-link" href="{{route('home.login')}}">申込む</a>
          </div>
        </div>
      </div>

      <div class="swiper-slide">
        <div class="fare blue-border">
          <p class="fare-title blue-balloon blue-stripe">無料プラン</p>
          <p class="fare-fee blue-color"><span class="fare-fee-span">0</span>円</p>
          <ul class="fare-list">
            <li class="fare-list-item">内容について入ります</li>
            <li class="fare-list-item">内容について入ります</li>
            <li class="fare-list-item">内容について入ります</li>
            <li class="fare-list-item">内容について入ります</li>
            <li class="fare-list-item">内容について入ります</li>
            <li class="fare-list-item">内容について入ります</li>
          </ul>
          <div class="fare-button blue-bg btn">
            <a href="fare-button-link" href="{{route('home.login')}}">申込む</a>
          </div>
        </div>
      </div>

      <div class="swiper-slide">
        <div class="fare red-border">
          <p class="fare-title red-balloon red-stripe">無料プラン</p>
          <p class="fare-fee red-color"><span class="fare-fee-span">0</span>円</p>
          <ul class="fare-list">
            <li class="fare-list-item">内容について入ります</li>
            <li class="fare-list-item">内容について入ります</li>
            <li class="fare-list-item">内容について入ります</li>
            <li class="fare-list-item">内容について入ります</li>
            <li class="fare-list-item">内容について入ります</li>
            <li class="fare-list-item">内容について入ります</li>
          </ul>
          <div class="fare-button red-bg btn">
            <a href="fare-button-link" href="{{route('home.login')}}">申込む</a>
          </div>
        </div>
      </div>

    </div>
    <div class="swiper-pagination relative"></div>
  </div>
</div>
@endsection
