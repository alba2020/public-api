<?php

use App\Order;
use App\Task;
use App\User;
use Faker\Generator as Faker;

$factory->define(\App\Action::class, function (Faker $faker) {

    return [
      'task_id' => Task::inRandomOrder()->first()->id,
      'worker_id' => User::inRandomOrder()->first()->id,
      'status' => Order::STATUS_CREATED
    ];
});
