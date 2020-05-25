<?php

namespace App\Consts;

// 定数管理
class Consts {

  // ----------APIリクエスト関連-----------
  const TIME_OUT = 60 * 60 * 24 * 30; //処理時間が30日過ぎたらタイムアウト
  const API_REQUEST_LIMIT_16 = 16;
  const API_REQUEST_LIMIT_900 = 900;
  const API_REQUEST_LIMIT_100000 = 100000;
  const SLEEP_TIME_15_MINS = 60 * 15;
  const SLEEP_TIME_24_HOURS = 60 * 60 * 24;
  const SMALL_TARGET_FF_MAX = 500;
  const MIDIUM_TARGET_FF_MAX = 2000;
  const SMALL_TARGET_FF_RATE_MAX = 1;
  const MIDIUM_TARGET_FF_RATE_MAX = 0.3;
  const ITEM_COUNT = 100;
  const DAY_PER_FOLLOW_MAX = 101;
  const NG_WORDS = ['収入を増やしたい', '投資', 'ネットビジネス'];
  const FOLLOWER_REGULATION = 4546;
  const FOLLOW_LIMITATION = 5000;
  const UNFOLLOW_REQUEST_MAX = 1000;
  const UNFOLLOW_REQUEST_HOUR = 50;

  // ----------エラーメッセージ-----------
  const ERROR_MSG_1 = '対象アカウントIDは入力必須です';
  const ERROR_MSG_2 = 'フォロー数上限に達しています';
}
