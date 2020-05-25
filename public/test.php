<?php
// 定期処理
// 全ユーザのその日のフォロー数、フォロワー数、新規フォロー数、新規フォロワー数を1日1回取得し、DBに保存
require __DIR__ . '/../vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
use Carbon\Carbon;

try {
  // Twittter APIキー類
  // テスト垢
  // $consumerKey = "9uou0b1xHmsM6PSbxRFVJuzhY";
  // $consumerSecret = "9F7CBA6sXQL9rq45haS8XSQ9QMb3BCg8nLT9RXGLi4OgGm4yw9";
  // $accessToken = "1255839612859117568-Bh4xZPmOWyAAfXUaE68fW9GBNkXMx5";
  // $accessTokenSecret = "OThPgNbPI6HwtfZMk61VEVZJU7L3PKZl6nLrvzdNCN6s2";
  // 本垢
  $consumerKey = "XvvtrkFQ945SOxNvNUZIIaOpz";
  $consumerSecret = "ZDqHKpY7fj5UJL7c2zOiycSg3nUKKQsJVEGWVtxrzQIvPUoTlp";
  $accessToken = "1182945112675848192-GMkUpVgAYDxWkVboHcqcEwWGvQqwgX";
  $accessTokenSecret = "0nesYRUsbUUou6uPo5VN3MKiLsOx8ibBD9w4D3HDtzIog";

  $connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

  // DB接続準備
  // $dsn = 'mysql:dbname=default;host=mysql;charset=utf8';
  // $user = 'root';
  // $password = 'root';
  // $options = [
  //   PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
  //   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  //   PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
  // ];
  // $dbh = new PDO($dsn, $user, $password, $options);

  $response = $connection->get("friendships/lookup", ['screen_name' => 'myama_net']);
  if (!isset($response)) {
    echo "APIデータ取得失敗\n";
  }

  var_dump($response);

  if (array_search('following', $response[0]->connections) !== false || array_search('following_requested', $response[0]->connections) !== false || array_search('followed_by', $response[0]->connections) !== false || array_search('blocking', $response[0]->connections) !== false) {
    echo "フォローしていたorされていたorブロックしてるのでスキップ\n";
    exit;
  }
  echo "第二関門：条件一致、フォロー\n";

} catch (PDOException $e) {
  echo $e->getMessage();
  exit;
}
