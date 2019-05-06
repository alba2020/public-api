<?php

namespace App;

class Bot extends BaseModel {

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function proxy() {
        return $this->belongsTo('App\Proxy');
    }
}
