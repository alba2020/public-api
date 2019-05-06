<?php

use App\Wallet;
use Faker\Generator as Faker;

$factory->define(\App\Transaction::class, function (Faker $faker) {

    return [
        'wallet_id' => Wallet::inRandomOrder()->first()->id,
        'type' => \App\Constants::INFLOW_TEST,
        'amount' => random_int(0, 10) + random_int(0, 100) / 100,
    ];
});
