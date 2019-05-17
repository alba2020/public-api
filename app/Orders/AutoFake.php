<?php

namespace App\Orders;

use App\Order;
use Tightenco\Parental\HasParent;

class AutoFake extends Order {

    use HasParent;

    public static function validate($details) {
        return true;
    }

    public function run() {
        return true;
    }
}
