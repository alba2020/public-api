<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proxy extends Model {
    protected $guarded = [];

    public function instagramUsers() {
        return $this->hasMany('App\User', 'instagram_proxy_id');
    }

    public function bots() {
        return $this->hasMany('App\Bot');
    }
}
