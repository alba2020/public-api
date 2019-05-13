<?php

namespace App;

class Proxy extends BaseModel {

    public function instagramUsers() {
        return $this->hasMany('App\User', 'instagram_proxy_id');
    }

    public function bots() {
        return $this->hasMany('App\Bot');
    }
}
