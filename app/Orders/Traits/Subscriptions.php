<?php

namespace App\Orders\Traits;

use App\Exceptions\ForeignServiceException;
use App\Order;
use App\Service;
use App\Services\InstagramScraperService;
use App\Services\NakrutkaService;
use App\SMM;

trait Subscriptions {

    public static function validate($details) {
        return SMM::withPosts(SMM::withMinAndMax(SMM::withUsername($details)));
    }

    public static function getPrice($service, $details) {
        $avg = ($details['min'] + $details['max']) / 2;
        $qty = $avg  * $details['posts'];
        return $service->getPrice($qty);
    }

    public static function getCost($service, $details) {
        $avg = ($details['min'] + $details['max']) / 2;
        $qty = $avg  * $details['posts'];
        return $service->getCost($qty);
    }

    public static function getImg($details) {
        $service = resolve(InstagramScraperService::class);
        return $service->getProfileImg($details['username']);
    }

    public static function getInstagramLogin($details) {
        return $details['username'];
    }

    public function run() {
        $username = $this->details['username'];
        $min = $this->details['min'];
        $max = $this->details['max'];
        $posts = $this->details['posts'];
        if (!isset($this->details['delay'])) {
            $delay = 0;
        } else {
            $delay = $this->details['delay'];
        }

        $nakrutka = resolve(NakrutkaService::class);
        $service = Service::findOrFail($this->service_id);
        $nakrutka->setApiService($service->nakrutka_id);

//        $username = str_random(16) . '_bad_name';
        $response = $nakrutka->addSub($username, $min, $max, $posts, $delay);
        if (!isset($response->order)) {
            throw ForeignServiceException::create([
                'text' => 'nakrutka did not return order',
                'response' => $response,
            ]);
        }

        $this->foreign_id = $response->order;

        $this->status = Order::STATUS_RUNNING;
        $this->save();
    }
}