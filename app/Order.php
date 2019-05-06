<?php

namespace App;

class Order extends BaseModel {

    protected $casts = [
        'details' => 'array'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function service() {
        return $this->belongsTo('App\Service');
    }
}
