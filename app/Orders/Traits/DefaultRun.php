<?php

namespace App\Orders\Traits;

use App\Exceptions\ForeignServiceException;
use App\Order;
use App\Service;
use App\Services\NakrutkaService;

trait DefaultRun {

    public function run() {
        $link = $this->details['link'];
        $quantity = $this->details['quantity'];

        $nakrutka = resolve(NakrutkaService::class);
        $service = Service::findOrFail($this->service_id);
        $nakrutka->setApiService($service->nakrutka_id);

        $link = rand(1, 10000);
        $response = $nakrutka->add('http://[BAD_URL]' . $link, $quantity);
        if (!isset($response->order)) {
            throw ForeignServiceException::create(['text' => 'nakrutka did not return order']);
        }

        $this->foreign_id = $response->order;

        $this->status = Order::STATUS_RUNNING;
        $this->save();
    }
}
