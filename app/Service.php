<?php

namespace App;

use App\Exceptions\CostException;

class Service extends BaseModel {

    protected $casts = [
        'info' => 'array',
        'price_list' => 'array',
    ];

    public static function getByType(string $type) {
        return self::where('type', $type)->first();
    }

    public function orders() {
        return $this->hasMany('App\Order');
    }

    public function getPrice($n) {
        $price = $this->price_list[1];
        $list = $this->price_list;
        ksort($list);
        foreach ($list as $k => $v) {
            if ($n < $k) {
                break;
            } else {
                $price = $v;
            }
        }
        return $price;
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
