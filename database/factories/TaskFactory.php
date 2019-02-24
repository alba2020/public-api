<?php

use Faker\Generator as Faker;
use App\User;
use App\Status;

$factory->define(\App\Task::class, function (Faker $faker) {

    return [
        'owner_id' =>  User::inRandomOrder()->first()->id,
        'platform' => 'fake',
        'url' => 'http://fake.platform/' . str_random(6),
        'type' => 'like',
        'n' => rand(1, 2),
        'speed' => rand(0, 9),
        'status' => Status::CREATED,
    ];
});
