<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model {
    protected $guarded = [];

    public function getPrice($n) {
        if ($n < 1000) {
            return $this->price;
        } else if ($n < 5000) {
            return $this->price_1k;
        } else if ($n < 10000) {
            return $this->price_5k;
        } else if ($n < 25000) {
            return $this->price_10k;
        } else if ($n < 50000) {
            return $this->price_25k;
        } else if ($n < 100000) {
            return $this->price_50k;
        } else if ($n >= 100000) {
            return $this->price_100k;
        } else {
            return null;
        }
    }
}
