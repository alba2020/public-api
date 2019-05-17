<?php

use App\Constants;
use App\Order;
use App\Service;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {

    $fake = Service::getByType(Constants::SERVICE_AUTO_FAKE);

    return [
        'uuid' => Order::makeUUID(),
        'service_id' => $fake->id,
        'type' => $fake->type,
    ];
});
