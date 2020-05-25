<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Store;
use Illuminate\Support\Facades\Auth;
use Abraham\TwitterOAuth\TwitterOAuth;
use App\Lib\My_func;
use App\Jobs\AutoFollow;
use Session;
use App\Consts\Consts;

class ManagementController extends Controller {
  public function dashboard($id) {
    $active_flg = 1;
    $user = User::where('twitter_id', $id)->first();
    $analyticsData = My_func::analytics($id);

    return view('management.dashboard', compact('active_flg', 'user', 'analyticsData'));
  }

  public function follow($id) {
    $active_flg = 2;
    $user = User::where('twitter_id', $id)->first();

    return view('management.follow', compact('active_flg', 'user'));
  }

  // 自動フォロー
  public function autoFollow(Request $request, $id) {
    $request->validate(['id' => 'required'], ['id.required' => Consts::ERROR_MSG_1]);

    $user = User::where('twitter_id', $id)->first();
    if ($user->follower < Consts::FOLLOWER_REGULATION) {
      if ($user->follow >= Consts::FOLLOW_LIMITATION) {
        Session::flash('message', Consts::ERROR_MSG_2);
        $active_flg = 2;
        return view('management.follow', compact('active_flg', 'user'));
      }
    } else {
      if ($user->follow >= $user->follower * 1.1) {
        Session::flash('message', Consts::ERROR_MSG_2);
        $active_flg = 2;
        return view('management.follow', compact('active_flg', 'user'));
      }
    }

    $screen_name = $request->id;
    $keyword = $request->keyword;
    AutoFollow::dispatch($screen_name, $keyword, $user);

    return redirect()->route('management.follow', ['id' => $id]);
  }

  public function followList(Request $request, $id, $order, $sort) {
    $active_flg = 3;
    $user = User::where('twitter_id', $id)->first();
    $filter = [];
    $followLists = My_func::followList($request, $user, $order, $sort);

    return view('management.followList', compact('active_flg', 'user', 'followLists', 'order', 'sort', 'filter'));
  }

  public function request(Request $request, $id, $order, $sort) {
    $user = User::where('twitter_id', $id)->first();
    $target = $request->target;
    $option = $request->option;

    if (!empty($request->filter) || !$request->filter) {
      // フィルター
      $followLists = My_func::followList($request, $user, $order, $sort);

      $active_flg = 3;
      $filter = $request->filter;
      return view('management.followList', compact('active_flg', 'user', 'followLists', 'order', 'sort', 'filter'));
    } elseif ($target) {
      // フォロー or アンフォローリクエスト
      My_func::request($request, $user, $target, $option);
      return redirect()->route('management.followList', ['id' => $id, 'order' => $order, 'sort' => $sort]);
    }
  }

  public function setting($id) {
    $active_flg = 4;
    $user = User::where('twitter_id', $id)->first();

    return view('management.setting', compact('active_flg', 'user'));
  }

  public function reload(Request $request, $id, $active_flg) {
    $user = User::where('twitter_id', $id)->first();

    $connection = new TwitterOAuth(config('services.twitter.client_id'), config('services.twitter.client_secret'), $user->access_token, $user->access_token_secret);
    $response = $connection->get('users/show', ['user_id' => $user->twitter_id]);

    $user->nickname = $response->screen_name;
    $user->name = $response->name;
    $user->img = $response->profile_image_url_https;
    $user->follow = $response->friends_count;
    $user->follower = $response->followers_count;
    $user->save();

    $request->session()->forget('follow');
    $request->session()->forget('follower');

    switch ($active_flg) {
      case 1:
        return redirect()->route('management.dashboard', ['id' => $user->twitter_id]);
      case 2:
        return redirect()->route('management.follow', ['id' => $user->twitter_id]);
      case 3:
        return redirect()->route('management.followList', ['id' => $user->twitter_id, 'order' => 'follower', 'sort' => 'follow_big']);
    }
  }

  public function withdrawal($id) {
    $user = User::where('twitter_id', $id)->first();
    $user->delete();

    return redirect()->route('auth.logout');
  }
}
