<?php

namespace App\Jobs;

// require __DIR__ . '/../../vendor/autoload.php';
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Consts\Consts;
use Abraham\TwitterOAuth\TwitterOAuth;
use Carbon\Carbon;
use App\Add;

class AutoFollow implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $keyword;
    protected $screen_name;
    protected $user;

    // タイムアウト：30日
    public $timeout = Consts::TIME_OUT;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($screen_name, $keyword, $user) {
      $this->screen_name = $screen_name;
      $this->keyword = $keyword;
      $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
      // 変数定義
      $connection = new TwitterOAuth(config('services.twitter.client_id'), config('services.twitter.client_secret'), $this->user->access_token, $this->user->access_token_secret);
      $filter1 = [];
      $filter2 = [];
      $filter3 = [];
      $filter4 = [];
      $request_count = 0;
      $item_count = 0;
      $ids= [];
      $ids_text = '';

      // フォロー数制限設定（フォロワー4546人以下は5000人、以上はフォロワー数 * 1.1）
      if ($this->user->follower <= Consts::FOLLOWER_REGULATION) {
        $follow_regulation = Consts::FOLLOW_LIMITATION - $this->user->follow;
      } else {
        $follow_regulation = ($this->user->follower * 1.1) - $this->user->follow;
      }

      try {
        // ---------------第一関門-------------------
        echo "第一関門開始\n";
        $params = [
          'cursor' => '-1',
          'count' => '200',
          'skip_status' => false,
          'screen_name' => $this->screen_name,
        ];

        do {
          // APIリクエスト制限回避（15requests/15mins）
          $request_count++;
          if ($request_count === Consts::API_REQUEST_LIMIT_16 - mt_rand(0, 2)) {
            echo "スリープタイム15分\n";
            sleep(Consts::SLEEP_TIME_15_MINS);
            $request_count = 1;
            echo "スリープタイム終了\n";
          }
          echo "リクエスト送信回数： " . $request_count . "\n";

          $response = $connection->get("followers/list", $params);
          if (!isset($response->users)) {
            echo "TwitterAPIの制限がかかっちゃってる！ごめんなさい！" . PHP_EOL;
            var_dump($response);
            exit;
          }
          foreach ($response->users as $item) {
            // NGワードがプロフに含まれているまたはプロフ文が空白の場合はスキップ
            if (strpos($item->description, Consts::NG_WORDS[0]) !== false || strpos($item->description, Consts::NG_WORDS[1]) !== false || strpos($item->description, Consts::NG_WORDS[2]) !== false || !$item->description) {
              echo "プロフにNGワード含むまたは空白のためスキップ\n";
              continue;

            } else {

              // フォロワー500人以下のユーザ
              if ($item->followers_count <= Consts::SMALL_TARGET_FF_MAX) {
                // フォロー500人以下またはFF比1以下ならフォロー、それ以外はスキップ
                if ($item->friends_count <= Consts::SMALL_TARGET_FF_MAX || $item->followers_count / $item->friends_count <= Consts::SMALL_TARGET_FF_RATE_MAX) {
                  $filter1[] = $item;
                  echo "フォロワー500人以下：条件一致、フォロー\n";
                  continue;
                }

                // フォロワー500人以上、2000人以下のユーザ
              } elseif ($item->followers_count > Consts::SMALL_TARGET_FF_MAX && $item->followers_count < Consts::MIDIUM_TARGET_FF_MAX) {
                // FF比0.3以下ならフォロー、それ以外はスキップ
                if ($item->followers_count / $item->friends_count <= Consts::MIDIUM_TARGET_FF_RATE_MAX) {
                  $filter1[] = $item;
                  echo "フォロワー501〜1999人：条件一致、フォロー\n";
                  continue;
                }
                echo "フォロワー501〜1999人：条件不一致、スキップ\n";
                continue;

                // フォロワー2000人以上はスキップ
              } else {
                echo "フォロワー2000人以上のためスキップ\n";
                continue;
              }

            }

          }
          echo "現在の要素数： " . count($filter1) . "\n";
        } while ($params["cursor"] = $response->next_cursor_str);

        echo "第一関門完了時の要素数： " . count($filter1) . "\n";
        $request_count = 0;

        // キーワード検索
        if ($this->keyword) {
          foreach ($filter1 as $key => $item) {

            if (!isset($item->status)) {
              if ((strpos($item->description, $this->keyword) === false && strpos($item->name, $this->keyword) === false)) {
                echo "status無し、キーワード検出無し、削除\n";
                unset($filter1[$key]);
              } else {
                echo "status無し、キーワード検出あり\n";
              }
            } else {
              if ((strpos($item->description, $this->keyword) === false && strpos($item->status->text, $this->keyword) === false && strpos($item->name, $this->keyword) === false) || !$item->status) {
                echo "キーワード検出無し、削除\n";
                unset($filter1[$key]);
              } else {
                echo "キーワード検出あり\n";
              }
            }

          }
        }

        // ---------------第二関門-------------------
        echo "第二関門開始\n";

        foreach ($filter1 as $item) {
          $item_count++;
          $ids[] = $item->screen_name;

          if ($item_count === Consts::ITEM_COUNT || $item === end($filter1)) {

            $request_count++;
            if ($request_count === Consts::API_REQUEST_LIMIT_16 - mt_rand(0, 2)) {
              echo "スリープタイム15分\n";
              sleep(Consts::SLEEP_TIME_15_MINS);
              $request_count = 1;
              echo "スリープタイム終了\n";
            }

            $ids_text = implode(",", $ids);
            $response = $connection->get("friendships/lookup", ['screen_name' => $ids_text]);
            if (!isset($response)) {
              echo "APIデータ取得失敗\n";
              break;
            }

            foreach ($response as $item) {

              if (array_search('following', $item->connections) !== false || array_search('following_requested', $item->connections) !== false || array_search('followed_by', $item->connections) !== false || array_search('blocking', $item->connections) !== false) {
                echo "フォローしていたorされていたorブロックしてるのでスキップ\n";
                continue;
              }

              echo "第二関門：条件一致、フォロー\n";
              $filter2[] = $item;
            }
            $ids= [];
            $ids_text = '';
            $item_count = 0;
          }
        }

        echo "第二関門完了時の要素数： " . count($filter2) . "\n";
        $request_count = 0;

        // ---------------第三関門-------------------
        echo "第三関門開始\n";
        foreach ($filter2 as $item) {

          $request_count++;
          if ($request_count === Consts::API_REQUEST_LIMIT_100000 - mt_rand(0, 2)) {
            echo "スリープタイム24時間\n";
            sleep(Consts::SLEEP_TIME_24_HOURS);
            $request_count = 1;
            echo "スリープタイム終了\n";
          }
          if ($request_count % (Consts::API_REQUEST_LIMIT_900 - mt_rand(0, 2)) === 0) {
            echo "スリープタイム15分\n";
            sleep(Consts::SLEEP_TIME_15_MINS);
            echo "スリープタイム終了\n";
            $request_count = 900;
          }

          $response = $connection->get("statuses/user_timeline", ['screen_name' => $item->screen_name, 'count' => 1]);

          if (!isset($response)) {
            echo "APIデータ取得失敗\n";
            break;
          } elseif (empty($response)) {
            echo "ツイートなし、スキップ\n";
            continue;
          } elseif (isset($response->error)) {
            echo "鍵付きアカウントのためスキップ\n";
            continue;
          } elseif (date('Y-m-d G:i:s', strtotime($response[0]->created_at)) < Carbon::now('Asia/Tokyo')->subDay(20)) {
            echo "20日以内にツイートなし、スキップ\n";
            continue;
          }

          echo "第三関門：条件一致、フォロー\n";
          $filter3[] = $item;
        }
        echo "第三関門完了時の要素数： " . count($filter3) . "\n";
        $request_count = 0;

        // ---------------第四関門-------------------
        echo "第四関門開始\n";
        $deleteUser_ids = [];
        $delete_users = Add::where('user_id', $this->user->twitter_id)->where('unfollow_flg', true)->get(['add_user_id']);

        foreach ($delete_users as $delete_user) {
          $deleteUser_ids[] = $delete_user->delete_user_id;
        }

        foreach ($filter3 as $item) {
          if (array_search($item->id, $deleteUser_ids) !== false) {
            echo "過去にアンフォロー歴あり、スキップ\n";
            continue;
          }
          echo "過去にアンフォロー歴なし、フォロー候補に追加\n";
          $filter4[] = $item;
        }

        echo "第四関門完了時の要素数： " . count($filter4) . "\n";

        // ---------------フォロー開始-------------------
        echo "自動フォロー開始\n";
        $followCount = 0;

        foreach ($filter4 as $item) {
          $request_count++;
          $follow_regulation--;

          if ($follow_regulation === -1) {
            echo "フォロー上限数に達したため、処理を終了します";
            break;
          }

          if ($request_count === Consts::DAY_PER_FOLLOW_MAX) {
            echo "スリープタイム24時間\n";
            sleep(Consts::SLEEP_TIME_24_HOURS);
            echo "スリープタイム終了\n";
            $request_count = 1;
          }

          $response = $connection->post("friendships/create", ['screen_name' => $item->screen_name, 'follow' => false]);

          if (!isset($response)) {
            echo "APIデータ取得失敗\n";
            break;
          } elseif (isset($response->errors)) {
            echo $response->errors[0]->message . "\n";
            echo "スリープタイム15分\n";
            var_dump($item);
            sleep(Consts::SLEEP_TIME_15_MINS);
            echo "スリープタイム終了\n";
            $response = $connection->post("friendships/create", ['screen_name' => $item->screen_name, 'follow' => false]);
          }

          Add::create([
            'user_id' => $this->user->twitter_id,
            'add_user_id' => $response->id
          ]);
          echo $response->name . "をフォローしました\n";
          $followCount++;
        }

        echo "フォローカウント： " . $followCount . "\nフォロー予定数： " . count($filter4) . "\n処理完了\n";

      } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
      }

    }
}
