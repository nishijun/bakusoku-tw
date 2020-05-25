<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Add extends Model {
  use SoftDeletes;

  protected $dates = ['deleted_at'];
  protected $fillable = ['user_id', 'add_user_id', 'unfollow_flg'];

  public function user() {
    return $this->belongsTo(User::class);
  }
}
