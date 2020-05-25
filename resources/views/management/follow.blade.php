@extends('layouts.management')
@section('title', '自動フォロー')
@section('content')
<div class="outer">

  <!-- ID・キーワード設定フォーム -->
  <div class="input">
    <form class="input-area" method="post" action="{{route('management.autoFollow', ['id' => $user->twitter_id])}}">
      @csrf
      <div class="input-area-form">
        <label for="id" class="input-area-form-label">対象アカウントID</label>
        <input id="id" type="text" name="id" placeholder="例）takapon_jp" class="input-area-form-input">
      </div>
      @if ($errors->has('id'))
        <p class="error-message" style="text-align:center;">{{ $errors->first('id') }}</p>
      @endif
      <div class="input-area-form">
        <label for="filter" class="input-area-form-label">キーワードフィルタ</label>
        <input id="filter" type="text" name="keyword" placeholder="例）プログラミング" class="input-area-form-input">
      </div>
      <div class="input-area-form">
        <input type="submit" value="自動フォロー開始" class="input-area-form-submit btn">
      </div>
    </form>
  </div>

  <div class="manuals">

    <!-- 使い方 -->
    <div class="manual">
      <div class="manual-title">使い方</div>
      <div class="manual-step">
        <div class="manual-step-number balloon">1</div>
        <div class="manual-step-text">対象アカウントのツイッターIDを入力</div>
      </div>
      <div class="manual-step">
        <div class="manual-step-number balloon">2</div>
        <div class="manual-step-text">フィルターするキーワードを入力※</div>
      </div>
      <div class="manual-step">
        <div class="manual-step-number">3</div>
        <div class="manual-step-text">自動フォロー開始をクリック</div>
      </div>
      <p class="manual-content">本ツールは、対象アカウントのフォロワーを自動フォローします。<br><br>もしフォロワーが多すぎる場合、キーワードフィルタを使用することで、そのキーワードを呟いているアカウントのみ自動フォローするようになります。（キーワード入力は省略可能です）</p>
    </div>

    <!-- アンフォロー -->
    <div class="manual">
      <div class="manual-title">アンフォロー</div>
      <ul class="manual-list">
        <li class="manual-list-item">フォロー返しがない</li>
        <li class="manual-list-item">フォロー解除された</li>
        <li class="manual-list-item">20日以上更新が途絶えている</li>
      </ul>
      <p class="manual-content">その場合、一週間後に自動でフォロー解除（アンフォロー）を行います。</p>
    </div>
  </div>
</div>
@endsection
