<?php
// 定期処理
// 全ユーザのその日のフォロー数、フォロワー数、新規フォロー数、新規フォロワー数を1日1回取得し、DBに保存
require __DIR__ . '/../vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
use Carbon\Carbon;

try {

  // Twittter APIキー類
  $consumerKey = "Vc8bIuvMlDNvHlmVPumD371K5";
  $consumerSecret = "q1OL380723ZGta5WwFl0LJ2su2uL0p75DDuqfw06Xl395QryZu";
  $accessToken = "1182945112675848192-HlbfK9iRcQ6FXHmPAQMZ2SsxJug818";
  $accessTokenSecret = "tQqlhfZCBXrqLZ0H4yUouHHa4SNt0bXVb5eg6YzJjtX4J";

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

  // 現在より30日以前のデータ消去
  $stmt = $dbh->prepare('DELETE FROM stores where created_at < (now() - INTERVAL 1 MONTH)');
  $stmt->execute();

  // 全ユーザデータ抽出
  $stmt = $dbh->prepare('SELECT * FROM users');
  $stmt->execute();
  $results = $stmt->fetchAll();

  if ($results) {

    foreach ($results as $result) {

      // TwitterAPIよりユーザデータ取得
      $user_info = $connection->get("users/show", ["user_id" => $result['twitter_id']]);

      if (isset($user_info->errors)) {
        //取得失敗
        echo "Error occurred. ";
        echo "Error message: " . $user_info->errors[0]->message;
      } else {
        //取得成功

        // 1日前のデータ取得
        $stmt = $dbh->prepare('SELECT follow, follower FROM stores WHERE created_at >= DATE(current_timestamp - INTERVAL 1 day) AND created_at < DATE(now()) AND user_id = :user_id');
        $stmt->execute([':user_id' => $result['twitter_id']]);
        $lastData = $stmt->fetch(PDO::FETCH_ASSOC);

        // 今日のデータをDBに保存
        $stmt = $dbh->prepare('INSERT INTO stores (user_id, follow, follower, new_follow, new_follower, created_at, updated_at) VALUES (:user_id, :follow, :follower, :new_follow, :new_follower, :created_at, :updated_at)');

        if ($lastData) {
          $judge = $stmt->execute([
            ':user_id' => $user_info->id,
            ':follow' => $user_info->friends_count,
            ':follower' => $user_info->followers_count,
            ':new_follow' => $user_info->friends_count - (int)$lastData['follow'],
            ':new_follower' => $user_info->followers_count - (int)$lastData['follower'],
            ':created_at' => Carbon::now('Asia/Tokyo'),
            ':updated_at' => Carbon::now('Asia/Tokyo')
          ]);
        } else {
          $judge = $stmt->execute([
            ':user_id' => $user_info->id,
            ':follow' => $user_info->friends_count,
            ':follower' => $user_info->followers_count,
            ':new_follow' => 0,
            ':new_follower' => 0,
            ':created_at' => Carbon::now('Asia/Tokyo'),
            ':updated_at' => Carbon::now('Asia/Tokyo')
          ]);
        }
        var_dump($judge);
      }
    }
  }
} catch (PDOException $e) {
  echo $e->getMessage();
  exit;
}
