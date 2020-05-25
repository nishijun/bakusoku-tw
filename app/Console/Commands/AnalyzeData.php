<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Abraham\TwitterOAuth\TwitterOAuth;
use Carbon\Carbon;
use App\Consts\Consts;
use App\User;
use App\Store;

class AnalyzeData extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'analyzedata';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = '全ユーザのその日のフォロー数、フォロワー数、新規フォロー数、新規フォロワー数を1日1回取得し、DBに保存';

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
    try {
      $request_count = 0;

      // 現在より30日以前のデータ消去
      Store::where('created_at', '<', Carbon::now('Asia/Tokyo')->subMonth(1))->delete();

      // 全ユーザ取得
      $users = User::all();
      if (!$users) {
        exit;
      }

      foreach ($users as $user) {
        $request_count++;
        if ($request_count === (Consts::API_REQUEST_LIMIT_900 - mt_rand(0, 2))) {
          echo "スリープタイム15分\n";
          sleep(Consts::SLEEP_TIME_15_MINS);
          echo "スリープタイム終了\n";
          $request_count = 1;
        }
        // Twitter APIデータ取得準備
        $connection = new TwitterOAuth(config('services.twitter.client_id'), config('services.twitter.client_secret'), $user->access_token, $user->access_token_secret);
        $response = $connection->get("users/show", ["user_id" => $user->twitter_id]);

        if (isset($response->errors)) {
          //取得失敗
          echo "Error occurred.\nError message: " . $response->errors[0]->message;
        } else {
          //取得成功

          // 1日前のデータ取得
          $lastData = Store::whereDate('created_at', Carbon::now('Asia/Tokyo')->subDay())->where('user_id', $user->twitter_id)->first();

          if ($lastData) {
            Store::create([
              'user_id' => $response->id,
              'follow' => $response->friends_count,
              'follower' => $response->followers_count,
              'new_follow' => $response->friends_count - (int)$lastData['follow'],
              'new_follower' => $response->followers_count - (int)$lastData['follower']
            ]);
          } else {
            Store::create([
              'user_id' => $response->id,
              'follow' => $response->friends_count,
              'follower' => $response->followers_count,
              'new_follow' => 0,
              'new_follower' => 0
            ]);
          }
        }
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
      exit;
    }
  }
}
