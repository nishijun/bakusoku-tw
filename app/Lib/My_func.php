<?php

namespace App\Lib;

use Illuminate\Support\Facades\Auth;
use Abraham\TwitterOAuth\TwitterOAuth;
use App\User;
use App\Store;

class My_func {
  protected $response;
  protected $order;
  protected $sort;

  // ダッシュボードグラフデータ取得
  public static function analytics($id) {
    $stores = Store::where('user_id', $id)->get();

    if ($stores->toArray()) {
      $dates = [];
      $follows = [];
      $followers = [];
      $new_follows = [];
      $new_followers = [];

      foreach ($stores as $store) {
        $dates[] = $store['created_at']->format('m/d');
        $follows[] = $store['follow'];
        $followers[] = $store['follower'];
        $new_follows[] = $store['new_follow'];
        $new_followers[] = $store['new_follower'];
      }

      $analyticsData = [$dates, $follows, $followers, $new_follows, $new_followers];

      return $analyticsData;
    } else {
      return false;
    }
  }

  // フォローリストページ データ取得
  public static function followList($request, $user, $order, $sort) {
    $follows = [];
    $followers = [];
    $sortLists = [];
    $lists = [];
    $followLists = [];
    // $request_count = 0;
    $connection = new TwitterOAuth(config('services.twitter.client_id'), config('services.twitter.client_secret'), $user->access_token, $user->access_token_secret);
    $params = [
      'cursor' => '-1',
      'count' => '200',
      'skip_status' => false,
      'user_id' => $user->twitter_id,
    ];

    // フォロワーリスト取得
    if (!$request->session()->has('follower')) {
      do {
        // APIリクエスト制限回避（15requests/15mins）
        // $request_count++;
        // if ($request_count === Consts::API_REQUEST_LIMIT_16 - mt_rand(0, 2)) {
        //   echo "スリープタイム15分\n";
        //   sleep(Consts::SLEEP_TIME_15_MINS);
        //   $request_count = 1;
        //   echo "スリープタイム終了\n";
        // }

        $response = $connection->get("followers/list", $params);
        if (!isset($response->users)) {
          echo "TwitterAPIの制限がかかっちゃってる！ごめんなさい！" . PHP_EOL;
          var_dump($response);
          exit;
        }

        foreach ($response->users as $item) {
          $followers[] = $item;
        }
      } while ($params["cursor"] = $response->next_cursor_str);
      $request->session()->put('follower', $followers);
    }

    // フォローリスト取得
    if (!$request->session()->has('follow')) {
      do {
        // APIリクエスト制限回避（15requests/15mins）
        // $request_count++;
        // if ($request_count === Consts::API_REQUEST_LIMIT_16 - mt_rand(0, 2)) {
        //   echo "スリープタイム15分\n";
        //   sleep(Consts::SLEEP_TIME_15_MINS);
        //   $request_count = 1;
        //   echo "スリープタイム終了\n";
        // }

        $response = $connection->get("friends/list", $params);
        if (!isset($response->users)) {
          echo "TwitterAPIの制限がかかっちゃってる！ごめんなさい！" . PHP_EOL;
          var_dump($response);
          exit;
        }

        foreach ($response->users as $item) {
          $follows[] = $item;
        }
      } while ($params["cursor"] = $response->next_cursor_str);

      // フォローされているか確認
      foreach ($follows as $follow) {
        if (array_search($follow->id, array_column($followers, 'id')) !== false) {
          $follow->followed_by = true;
        } else {
          $follow->followed_by = false;
        }
      }

      $request->session()->put('follow', $follows);
    }

    $lists = ($order === 'follow') ? $request->session()->get('follow') : $request->session()->get('follower');

    // フィルター処理
    if (!empty($request->filter)) {
      foreach ($request->filter as $item) {
        // フォローのみ
        if ($item === 'フォローのみ') {
          $lists = array_filter($lists, function($list) {
            return $list->following;
          });
        }

        // フォロワーのみ
        if ($item === 'フォロワーのみ') {
          $lists = array_filter($lists, function($list) {
            return $list->followed_by;
          });
        }

        // フォロワーが1000人以上
        if ($item === 'フォロワーが1000人以上') {
          $lists = array_filter($lists, function($list) {
            return $list->followers_count >= 1000;
          });
        }

        // フォローが1000人以上
        if ($item === 'フォローが1000人以上') {
          $lists = array_filter($lists, function($list) {
            return $list->friends_count >= 1000;
          });
        }
      }
    }

    // ソート
    foreach ($lists as $list) {
      switch ($sort) {
        case 'follower_big':
        case 'follower_small':
          $sortLists[] = $list->followers_count;
          break;
        case 'follow_big':
        case 'follow_small':
          $sortLists[] = $list->friends_count;
          break;
        case 'tweet_big':
        case 'tweet_small':
          $sortLists[] = $list->statuses_count;
          break;
      }
    }
    switch ($sort) {
      case 'follower_big':
      case 'follow_big':
      case 'tweet_big':
        array_multisort($sortLists, SORT_DESC, $lists);
        break;
      case 'follower_small':
      case 'follow_small':
      case 'tweet_small':
        array_multisort($sortLists, SORT_ASC, $lists);
        break;
    }

    return $lists;
  }

  // フォローリストページ フォロー＆アンフォロー操作
  public static function request($request, $user, $target, $option) {
    $connection = new TwitterOAuth(config('services.twitter.client_id'), config('services.twitter.client_secret'), $user->access_token, $user->access_token_secret);

    if ($option === 'follow') {
      $response = $connection->post('friendships/create', ['user_id' => $target]);
    } elseif ($option === 'unfollow') {
      $response = $connection->post('friendships/destroy', ['user_id' => $target]);
    }

    if (isset($response->errors)) {
      echo "TwitterAPIの制限がかかっちゃってる！ごめんなさい！" . PHP_EOL;
      var_dump($response);
      exit;
    }

    $request->session()->forget('follow');
    $request->session()->forget('follower');
  }
}
