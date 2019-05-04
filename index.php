<?php
// インポート
require('vendor/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;
use josegonzalez\Dotenv\Loader as Dotenv;
$appDir = __DIR__;
Dotenv::load([
    'filepath' =>  $appDir . '/.env',
    'toEnv' => true
]);

$apikey = $_ENV['API_KEY'];
$apisecretkey = $_ENV['API_SECRET_KEY'];
$accesstoken = $_ENV['ACCESS_TOKEN'];
$accesstokensecret = $_ENV['ACCESS_TOKEN_SECRET'];

// インスタンス作成
$connection = new TwitterOAuth(
    $apikey,
    $apisecretkey,
    $accesstoken,
    $accesstokensecret
);

$keyWordList = array(
    "WEB広告",
    "広告運用",
    "ASP",
);
$randKw = shuffle($keyWordList);
$randKw = current($keyWordList);
var_dump($randKw);
// タイムライン取得
$getTimeline = $connection->OAuthRequest(
    'https://api.twitter.com/1.1/search/tweets.json',
    'GET',
    array(
        "q" => $randKw,
        "lang" => "ja",
        "locale" => "ja",
        "result_type" => "mixed",
        "count" => "30"
    )
);

// jsonに変換
$tweet_json = json_decode($getTimeline);

$id_array = $tweet_json->statuses;

// 配列の中身の数をカウント
$id_arrayCount = count($id_array);

// 配列の中身の数
for ($i=0; $i < $id_arrayCount; $i++) {
    // idをここで見てる
    $id = $tweet_json->statuses[$i]->id;
    // RTする
    $connection->post('favorites/create', ['id' => $id]);
    sleep(60);
}