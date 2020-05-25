<?php

use Illuminate\Support\Facades\Route;
// use App\Jobs\AutoFollow;

// <!-- Home -->
// トップページ
Route::get('/', ['as' => 'home.top', 'uses' => 'HomeController@index']);

// ログインページ表示
Route::get('/login', ['as' => 'home.login', 'uses' => 'HomeController@login']);

// 特商法ページ表示
Route::get('/tokutei', ['as' => 'home.tokutei', 'uses' => 'HomeController@tokutei']);

// Twitterログイン
Route::get('login/twitter', ['as' => 'auth.login', 'uses' => 'HomeController@redirectToProvider']);
Route::get('login/twitter/callback', ['as' => 'auth.callback', 'uses' => 'HomeController@handleProviderCallback']);

// ログアウト
Route::get('login/twitter/logout', ['as' => 'auth.logout', 'uses' => 'HomeController@logout']);

// <!-- 管理画面 -->
Route::group(['middleware' => 'auth'], function() {

  // ※他ユーザによるなりすましアクセス防止
  Route::group(['middleware' => 'preventAccess'], function() {
    // ダッシュボード表示
    Route::get('/{id}/dashboard', ['as' => 'management.dashboard', 'uses' => 'ManagementController@dashboard']);

    // 自動フォローページ表示
    Route::get('/{id}/follow', ['as' => 'management.follow', 'uses' => 'ManagementController@follow']);

    // フォローリストページ表示
    Route::get('/{id}/followList/{order}/{sort}', ['as' => 'management.followList', 'uses' => 'ManagementController@followList']);

    // アカウント管理ページ表示
    Route::get('/{id}/setting', ['as' => 'management.setting', 'uses' => 'ManagementController@setting']);

    // 自動フォロー操作
    Route::post('/{id}/follow', ['as' => 'management.autoFollow', 'uses' => 'ManagementController@autoFollow']);

    // フォローorアンフォロー操作
    Route::post('/{id}/followList/{order}/{sort}', ['as' => 'management.request', 'uses' => 'ManagementController@request']);

    // リロード
    Route::get('/{id}/{active_flg}/reload', ['as' => 'management.reload', 'uses' => 'ManagementController@reload']);

    // アカウント削除
    Route::post('/{id}/setting', ['as' => 'management.withdrawal', 'uses' => 'ManagementController@withdrawal']);
  });

});
