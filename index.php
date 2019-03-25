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

// タイムライン取得（キーワード：Uber Eats）
$getTimeline = $connection->OAuthRequest(
    'https://api.twitter.com/1.1/search/tweets.json',
    'GET',
    array(
        "q" => "ちくわ",
        "lang" => "ja",
        "locale" => "ja",
        "result_type" => "mixed",
        "count" => "20"
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
    $retweet = $connection->OAuthRequest(
        'https://api.twitter.com/1.1/statuses/retweet/'.$id.'.json',
        "POST",
        $id_array
    );
    sleep(5);
}