<?php

use App\Task;
use Faker\Generator as Faker;
use App\User;
use App\Status;

$factory->define(\App\Action::class, function (Faker $faker) {

    return [
      'task_id' => Task::inRandomOrder()->first()->id,
      'worker_id' => User::inRandomOrder()->first()->id,
      'status' => Status::CREATED
    ];

});
