<?php

namespace App;

use App\Exceptions\CostException;

class Service extends BaseModel {

    protected $casts = [
        'info' => 'array',
    ];

    public static function getByType(string $type) {
        return self::where('type', $type)->first();
    }

    public function orders() {
        return $this->hasMany('App\Order');
    }

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
            return $this->price;
        }
    }

    /**
     * @param $n
     * @return array
     * @throws CostException
     */
    public function getCost($n): float {
        return static::computeCost($this, $n)['cost'];
    }

    /**
     * @param $service
     * @param $n
     * @throws CostException
     */
    public static function computeCost($service, $n): array {
        if (!$service) {
            throw CostException::create(['text' => 'Service not found']);
        }

        if ($n < $service->min || $n > $service->max) {
            throw CostException::create([
                'text' => "n must be in [$service->min, $service->max], but was $n"
            ]);
        }

        $price = $service->getPrice($n);
        if (!$price) {
            throw CostException::create(['text' => "bad price: $price, n: $n"]);
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
