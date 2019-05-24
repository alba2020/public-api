<?php

namespace App\Orders\Traits;

trait DefaultPriceAndCost {

    public static function getPrice($service, $details) {
        $qty = $details['quantity'];
        return $service->getPrice($qty);
    }

    public static function getCost($service, $details) {
        $qty = $details['quantity'];
        return $service->getCost($qty);
    }
}
