<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model {
  use SoftDeletes;

  protected $dates = ['deleted_at'];
  protected $fillable = ['user_id', 'follow', 'follower', 'new_follow', 'new_follower'];

  public function user() {
    return $this->belongsTo(User::class);
  }
}
