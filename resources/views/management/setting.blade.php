@extends('layouts.management')
@section('title', 'アカウント管理')
@section('content')
<div class="outer">
  <div class="setting">
    <div class="setting-title">アカウント管理</div>
    <ul class="setting-list">
      <li class="setting-list-item js-click-confirm">
        アカウント削除
        <form class="js-click-confirm-target" action="{{route('management.withdrawal', ['id' => $user->twitter_id])}}" method="post">
          @csrf
          <input type="hidden" name="user_id" value="{{ $user->twitter_id }}">
        </form>
      </li>
    </ul>
    <div class="setting-title">プラン / 支払い</div>
    <ul class="setting-list">
      <li class="setting-list-item">プラン変更</li>
    </ul>
  </div>
</div>
@endsection
