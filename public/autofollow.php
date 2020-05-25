<?php
// 定期処理
// 全ユーザのその日のフォロー数、フォロワー数、新規フォロー数、新規フォロワー数を1日1回取得し、DBに保存
require __DIR__ . '/../vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
use Carbon\Carbon;

try {

  // Twittter APIキー類
  $consumerKey = "9uou0b1xHmsM6PSbxRFVJuzhY";
  $consumerSecret = "9F7CBA6sXQL9rq45haS8XSQ9QMb3BCg8nLT9RXGLi4OgGm4yw9";
  $accessToken = "1255839612859117568-Bh4xZPmOWyAAfXUaE68fW9GBNkXMx5";
  $accessTokenSecret = "OThPgNbPI6HwtfZMk61VEVZJU7L3PKZl6nLrvzdNCN6s2";

  $connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

  // DB接続準備
  $dsn = 'mysql:dbname=default;host=mysql;charset=utf8';
  $user = 'root';
  $password = 'root';
  $options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
  ];
  $dbh = new PDO($dsn, $user, $password, $options);

  $filter1 = [];
  $filter2 = [];
  $filter3 = [];
  $counter = 0;

  // ---------------第一関門-------------------
  echo "第一関門開始\n";
  $params = [
    'cursor' => '-1',
    'count' => '200',
    'skip_status' => 'true',
    'screen_name' => 'harunatzy',
  ];

  do {
    // APIリクエスト制限回避（15requests/15mins）
    $counter++;
    if ($counter === 16) {
      echo "スリープタイム15分\n";
      sleep(60 * 15);
      $counter = 1;
      echo "スリープタイム終了\n";
    }
    echo "リクエスト送信回数： " . $counter . "\n";

    $response = $connection->get("followers/list", $params);
    if (!isset($response->users)) {
        echo "TwitterAPIの制限がかかっちゃってる！ごめんなさい！" . PHP_EOL;
        break;
    }

    foreach ($response->users as $item) {

      // NGワードがプロフに含まれているまたはプロフ文が空白の場合はスキップ
      if (strpos($item->description, '収入を増やしたい') !== false || strpos($item->description, '投資') !== false || strpos($item->description, 'ネットビジネス') !== false || !$item->description) {
        echo "プロフにNGワード含むまたは空白のためスキップ\n";
        continue;

      } else {

        // フォロワー500人以下のユーザ
        if ($item->followers_count <= 500) {
          // フォロー500人以下またはFF比1以下ならフォロー、それ以外はスキップ
          if ($item->friends_count <= 500 || $item->followers_count / $item->friends_count <= 1) {
            $filter1[] = $item;
            echo "フォロワー500人以下：条件一致、フォロー\n";
            continue;
          }

        // フォロワー500人以上、2000人以下のユーザ
        } elseif ($item->followers_count > 500 && $item->followers_count < 2000) {
          // FF比0.3以下ならフォロー、それ以外はスキップ
          if ($item->followers_count / $item->friends_count <= 0.3) {
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
  $counter = 0;

  // ---------------第二関門-------------------
  echo "第二関門開始\n";
  $ids= [];
  $ids_text = '';
  $itemCounter = 0;

  foreach ($filter1 as $item) {
    $itemCounter++;
    $ids[] = $item->screen_name;

    if ($itemCounter === 100 || $item === end($filter1)) {

      $counter++;
      if ($counter === 16) {
        echo "スリープタイム15分\n";
        sleep(60 * 15);
        $counter = 1;
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
      $itemCounter = 0;
    }
  }

  echo "第二関門完了時の要素数： " . count($filter2) . "\n";
  $counter = 0;

  // ---------------第三関門-------------------
  echo "第三関門開始\n";
  foreach ($filter2 as $item) {

    $counter++;
    if ($counter === 100000) {
      echo "スリープタイム24時間\n";
      sleep(60 * 60 * 24);
      $counter = 1;
      echo "スリープタイム終了\n";
    }
    if ($counter % 900 === 0) {
      echo "スリープタイム15分\n";
      sleep(60 * 15);
      echo "スリープタイム終了\n";
    }

    $response = $connection->get("statuses/user_timeline", ['screen_name' => $item->screen_name, 'count' => 1]);

    if (!isset($response)) {
      echo "APIデータ取得失敗\n";
      break;
    } elseif (empty($response)) {
      echo "ツイートなし、スキップ\n";
      var_dump($response);
      continue;
    } elseif (isset($response->error)) {
      echo "鍵付きアカウントのためスキップ\n";
      var_dump($response);
      continue;
    } elseif (date('Y-m-d G:i:s', strtotime($response[0]->created_at)) < Carbon::now('Asia/Tokyo')->subDay(20)) {
      echo "20日以内にツイートなし、スキップ\n";
      var_dump($response[0]->created_at);
      continue;
    }

    echo "第三関門：条件一致、フォロー\n";
    $filter3[] = $item;
    var_dump($response[0]->created_at);
  }
  echo "第三関門完了時の要素数： " . count($filter3) . "\n";
  $counter = 0;

  // ---------------フォロー開始-------------------
  echo "自動フォロー開始\n";
  $followCount = 0;

  foreach ($filter3 as $item) {
    $counter++;

    if ($counter === 101) {
      echo "スリープタイム24時間\n";
      sleep(60 * 60 * 24);
      echo "スリープタイム終了\n";
      $counter = 1;
    }

    $response = $connection->post("friendships/create", ['screen_name' => $item->screen_name, 'follow' => false]);

    if (!isset($response)) {
      echo "APIデータ取得失敗\n";
      break;
    } elseif (isset($response->errors)) {
      echo $response->errors[0]->message . "\n";
      continue;
    }

    echo $response->name . "をフォローしました\n";
    $followCount++;
  }

  echo "フォローカウント： " . $followCount . "\nフォロー予定数： " . $filter3 . "\n";
} catch (PDOException $e) {
  echo $e->getMessage();
  exit;
}
