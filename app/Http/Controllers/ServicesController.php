<?php

namespace App\Http\Controllers;

use App\Service;

class ServicesController extends Controller {

    public function index() {
        return Service::all();
    }

    public function cost($service_id, $n) {
        $service = Service::find($service_id);
        if(!$service) {
            return response()->json([
                'error' => 'service not found'
            ]);
        }

        if ($n < $service->min || $n > $service->max) {
            return response()->json([
                'error' => 'n must be between ' . $service->min . ' and ' . $service->max,
                'n' => $n,
            ]);
        }

        $price = $service->getPrice($n);
        if (!$price) {
            return response()->json([
                'error' => 'bad price',
                'price' => $price,
                'n' => $n,
            ]);
        }
        $cost = $price * $n;
        $full_cost = $service->getPrice(1) * $n;
        $economy = $full_cost - $cost;

        return response()->json([
            'success' => [
                'service id' => $service->id,
                'service type' => $service->type,
                'n' => $n,
                'cost' => round($cost, 2, PHP_ROUND_HALF_DOWN),
                'economy' => round($economy, 2, PHP_ROUND_HALF_DOWN),
            ]
        ]);
    }
}
