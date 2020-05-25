<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Abraham\TwitterOAuth\TwitterOAuth;
use Carbon\Carbon;
use App\Consts\Consts;
use App\User;
use App\Add;

class DeleteUsers extends Command {
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'deleteuser';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = '自動フォローしたユーザの中で条件に一致する（非アクティブ等）ユーザをフォロー解除';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
      parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
    $request_count = 0;
    $item_count = 0;
    $ids = [];
    $ids_text = '';
    $unfollow_candidates = [];
    $filter = [];

    // 全ユーザ取得
    $users = User::all();
    if (!$users) {
      exit;
    }

    foreach ($users as $user) {
      echo $user->name . "\n";
      // Twitter APIデータ取得準備
      $connection = new TwitterOAuth(config('services.twitter.client_id'), config('services.twitter.client_secret'), $user->access_token, $user->access_token_secret);
      $response = $connection->get("users/show", ["user_id" => $user->twitter_id]);

      // 自動フォロー後、7日以上経過しているユーザを取得
      $followed_users = Add::where('user_id', $user->twitter_id)->where('created_at', '<=', Carbon::now('Asia/Tokyo')->subDay(7))->where('unfollow_flg', false)->get();
      if (!$followed_users) {
        echo "該当ユーザなし\n";
        continue;
      }

      // フォローを返してくれているか否か判断
      foreach ($followed_users as $followed_user) {
        $item_count++;
        $ids[] = $followed_user->add_user_id;

        if ($item_count % Consts::ITEM_COUNT === 0 || $item_count === count($followed_users)) {

          $request_count++;
          if ($request_count === Consts::API_REQUEST_LIMIT_16 - mt_rand(0, 2)) {
            echo "スリープタイム15分\n";
            sleep(Consts::SLEEP_TIME_15_MINS);
            $request_count = 1;
            echo "スリープタイム終了\n";
          }

          $ids_text = implode(",", $ids);
          $response = $connection->get("friendships/lookup", ['user_id' => $ids_text]);
          if (!isset($response)) {
            echo "APIデータ取得失敗\n";
            break;
          }

          foreach ($response as $item) {
            if (array_search('followed_by', $item->connections) !== false) {
              echo "フォローされているので次のフィルターに持ち越し\n";
              $filter[] = $item;
              continue;
            } elseif (!$item->connections) {
              echo "空配列\n";
              echo $item . "\n";
              $unfollow_candidates[] = $item;
              continue;
            }

            echo "フォローされていないのでフォロー解除候補に追加\n";
            $unfollow_candidates[] = $item;
          }
          $ids= [];
          $ids_text = '';
          // $item_count = 0;
        }
      }
      echo "現時点のフォロー解除候補者数： " . count($unfollow_candidates) . "\n";
      echo "次のフィルター該当者数： " . count($filter) . "\n";
      $request_count = 0;

      // アクティブユーザ（20日以内のツイート有無）か否か判断
      foreach ($filter as $item) {
        $request_count++;
        if ($request_count === Consts::API_REQUEST_LIMIT_900 - mt_rand(0, 2)) {
          echo "スリープタイム15分\n";
          sleep(Consts::SLEEP_TIME_15_MINS);
          echo "スリープタイム終了\n";
          $request_count = 1;
        }

        $response = $connection->get("statuses/user_timeline", ['user_id' => $item->id, 'count' => 1]);
        if (!isset($response)) {
          echo "APIデータ取得失敗\n";
          break;
        } elseif (isset($response->errors) || empty($response)) {
          echo "鍵付きアカウントまたはツイートなし、フォロー解除候補に追加\n";
          $unfollow_candidates[] = $item;
          continue;
        } elseif (date('Y-m-d G:i:s', strtotime($response[0]->created_at)) >= Carbon::now('Asia/Tokyo')->subDay(20)) {
          echo "20日以内にツイートしているのでスキップ\n";
          continue;
        }

        echo "20日以内にツイートなし、フォロー解除候補に追加\n";
        var_dump($response);
        $unfollow_candidates[] = $item;
      }

      echo "フィルター後のフォロー解除候補者数： " . count($unfollow_candidates) . "\n";
      $request_count = 0;

      // アンフォロー
      foreach ($unfollow_candidates as $item) {
        $request_count++;
        if ($request_count === Consts::UNFOLLOW_REQUEST_MAX) {
          echo "スリープタイム24時間\n";
          sleep(Consts::SLEEP_TIME_24_HOURS);
          echo "スリープタイム終了\n";
          $request_count = 1;
        }
        $response = $connection->post("friendships/destroy", ['user_id' => $item->id]);

        if (!isset($response)) {
          echo "APIデータ取得失敗\n";
          break;
        } elseif ($request_count % Consts::UNFOLLOW_REQUEST_HOUR === 0 || isset($response->errors)) {
          var_dump($response);
          echo "スリープタイム15分\n";
          sleep(Consts::SLEEP_TIME_15_MINS);
          echo "スリープタイム終了\n";
        }

        $unfollowed_user = Add::where('user_id', $user->twitter_id)->where('add_user_id', $item->id)->first();
        $unfollowed_user->unfollow_flg = true;
        $unfollowed_user->save();
        echo $response->name . "をアンフォローしました\n";
      }
    }
  }
}
