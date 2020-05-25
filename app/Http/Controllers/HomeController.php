<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Socialite;

class HomeController extends Controller
{
    public function index() {
      return view('home.index');
    }

    public function login() {
      return view('home.login');
    }

    public function tokutei() {
      return view('home.tokutei');
    }

    // Twitterログイン
    public function redirectToProvider() {
      return Socialite::driver('twitter')->redirect();
    }

    // コールバック
    public function handleProviderCallback() {
      try {
        $twitterUser = Socialite::driver('twitter')->user();
      } catch (Exception $e) {
        return redirect('login/twitter');
      }
      $user = User::where('twitter_id', $twitterUser->id)->first();

      if (!$user) {
        $user = User::create([
          'twitter_id' => $twitterUser->id,
          'nickname' => $twitterUser->nickname,
          'name' => $twitterUser->name,
          'img' => $twitterUser->avatar,
          'follow' => $twitterUser->user['friends_count'],
          'follower' => $twitterUser->user['followers_count'],
          'access_token' => $twitterUser->token,
          'access_token_secret' => $twitterUser->tokenSecret,
        ]);
      } else {
        $user->nickname = $twitterUser->nickname;
        $user->name = $twitterUser->name;
        $user->img = $twitterUser->avatar;
        $user->follow = $twitterUser->user['friends_count'];
        $user->follower = $twitterUser->user['followers_count'];
        $user->access_token = $twitterUser->token;
        $user->access_token_secret = $twitterUser->tokenSecret;
        $user->save();
      }

      Auth::login($user);
      return redirect()->route('management.dashboard', ['id' => $user->twitter_id]);
    }

    // ログアウト
    public function logout(Request $request) {
      $request->session()->flush();
      Auth::logout();
      return redirect()->route('home.login');
    }
}
