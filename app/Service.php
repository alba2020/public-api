<?php

namespace App;

use App\Exceptions\CostException;
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

    /**
     * @param $service
     * @param $n
     * @throws CostException
     */
    public static function getCost($service, $n) {
        if (!$service) {
            throw new CostException("service not found");
        }

        if ($n < $service->min || $n > $service->max) {
            throw new CostException(
                "n must be in [$service->min, $service->max], but was $n");
        }

        $price = $service->getPrice($n);
        if (!$price) {
            throw new CostException("bad price: $price, n: $n");
        }

        $cost = $price * $n;
        $full_cost = $service->getPrice(1) * $n;
        $economy = $full_cost - $cost;

        return [
            'service_id' => $service->id,
            'service_type' => $service->type,
            'n' => $n,
            'cost' => round($cost, 2, PHP_ROUND_HALF_DOWN),
            'economy' => round($economy, 2, PHP_ROUND_HALF_DOWN),
        ];
    }
}
