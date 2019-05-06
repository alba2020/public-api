<?php

namespace App;

class Wallet extends BaseModel
{
    public function user() {
        return $this->belongsTo('App\User');
    }

    public function transactions() {
        return $this->hasMany('App\Transaction');
    }
}
