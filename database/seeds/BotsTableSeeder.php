<?php

use App\Bot;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class BotsTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker) {
        Bot::truncate();

        Bot::create([
            'login' => $faker->userName,
            'password' => $faker->password,
            'user_id' => 101,
        ]);

        Bot::create([
            'login' => $faker->userName,
            'password' => $faker->password,
            'user_id' => 101,
        ]);

        Bot::create([
            'login' => $faker->userName,
            'password' => $faker->password,
            'user_id' => 102,
        ]);

        Bot::create([
            'login' => $faker->userName,
            'password' => $faker->password,
            'user_id' => 102,
        ]);

        Bot::create([
            'login' => $faker->userName,
            'password' => $faker->password,
            'user_id' => 103,
        ]);

        Bot::create([
            'login' => $faker->userName,
            'password' => $faker->password,
            'user_id' => 103,
        ]);

        Bot::create([
            'login' => $faker->userName,
            'password' => $faker->password,
            'user_id' => 103,
        ]);
    }
}
