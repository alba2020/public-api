<?php

namespace App;

class Wallet extends BaseModel {
    public function user() {
        return $this->belongsTo('App\User');
    }

    public function transactions() {
        return $this->hasMany('App\Transaction');
    }

    public function getBalanceAttribute() {
        $balance = $this->transactions()->get()->sum('amount');
        $rounded = round($balance, 2, PHP_ROUND_HALF_DOWN);
        return $rounded;
    }

    public function applyTransaction(
        string $type, float $amount, string $comment='') {

        return Transaction::create([
            'wallet_id' => $this->id,
            'type' => $type,
            'amount' => $amount,
            'comment' => $comment
        ]);
    }
}
