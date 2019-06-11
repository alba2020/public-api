<?php

use App\PremiumStatus;
use Illuminate\Database\Seeder;

class PremiumStatusesTableSeeder extends Seeder {

    public function run() {
        PremiumStatus::create([
            'name' => 'Базовый',

            'online_support' => 1,
            'friends_cashback' => 1,
            'event_bonuses' => 0,

            'discount_likes' => 0,
            'discount_views' => 0,
            'discount_subs' => 0,
            'discount_rest' => 0,

            'premium_services' => 0,
            'bonus_five_percent' => 0,
            'personal_manager' => 0,

            'cash' => 0,
        ]);

        PremiumStatus::create([
            'name' => 'Персональный',

            'online_support' => 1,
            'friends_cashback' => 1,
            'event_bonuses' => 1,

            'discount_likes' => 5,
            'discount_views' => 7,
            'discount_subs' => 5,
            'discount_rest' => 3,

            'premium_services' => 0,
            'bonus_five_percent' => 0,
            'personal_manager' => 0,

            'cash' => 3000,
        ]);
    }
}
