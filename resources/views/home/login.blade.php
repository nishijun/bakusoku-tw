@extends('layouts.top')
@section('title', 'ログイン')
@section('content')
<div class="login">
  <div class="login-inner">
    <img src="{{asset('img/login.png')}}" alt="Login's logo" class="login-inner-logo">
    <div class="login-inner-title">
      <div class="login-inner-title-border"></div>
      <p class="login-inner-title-paragraph">Login</p>
      <div class="login-inner-title-border"></div>
    </div>
    <div class="btn twitter">
      <a href="{{route('auth.login')}}" class="twitter-link"><i class="fab fa-twitter"></i>Twitterでログイン</a>
    </div>
  </div>
</div>
@endsection
