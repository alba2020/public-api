<?php

namespace App;

class Transaction extends BaseModel {

    public function wallet() {
        return $this->belongsTo('App\Wallet');
    }
}
