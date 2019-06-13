<?php

use App\Constants;
use App\PremiumStatus;
use Illuminate\Database\Seeder;

class PremiumStatusesTableSeeder extends Seeder {

    public function run() {
        PremiumStatus::create([
            'name' => 'Базовый',

            'online_support' => 1,
            'friends_cashback' => 1,
            'event_bonuses' => 0,

            'discount' => [
                Constants::GROUP_LIKES => 0,
                Constants::GROUP_VIEWS => 0,
                Constants::GROUP_SUBS => 0,
                Constants::GROUP_OTHER => 0,
            ],

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

            'discount' => [
                Constants::GROUP_LIKES => 5,
                Constants::GROUP_VIEWS => 7,
                Constants::GROUP_SUBS => 5,
                Constants::GROUP_OTHER => 3,
            ],

            'premium_services' => 0,
            'bonus_five_percent' => 0,
            'personal_manager' => 0,

            'cash' => 3000,
        ]);

        PremiumStatus::create([
            'name' => 'Премиум',

            'online_support' => 1,
            'friends_cashback' => 1,
            'event_bonuses' => 1,

            'discount' => [
                Constants::GROUP_LIKES => 10,
                Constants::GROUP_VIEWS => 15,
                Constants::GROUP_SUBS => 7,
                Constants::GROUP_OTHER => 7,
            ],

            'premium_services' => 1,
            'bonus_five_percent' => 0,
            'personal_manager' => 0,

            'cash' => 10000,
        ]);

        PremiumStatus::create([
            'name' => 'Блогер',

            'online_support' => 1,
            'friends_cashback' => 1,
            'event_bonuses' => 1,

            'discount' => [
                Constants::GROUP_LIKES => 17,
                Constants::GROUP_VIEWS => 30,
                Constants::GROUP_SUBS => 13,
                Constants::GROUP_OTHER => 10,
            ],

            'premium_services' => 1,
            'bonus_five_percent' => 1,
            'personal_manager' => 0,

            'cash' => 25000,
        ]);

        PremiumStatus::create([
            'name' => 'Элитный',

            'online_support' => 1,
            'friends_cashback' => 1,
            'event_bonuses' => 1,

            'discount' => [
                Constants::GROUP_LIKES => 30,
                Constants::GROUP_VIEWS => 50,
                Constants::GROUP_SUBS => 20,
                Constants::GROUP_OTHER => 17,
            ],

            'premium_services' => 1,
            'bonus_five_percent' => 1,
            'personal_manager' => 1,

            'cash' => 50000,
        ]);
    }
}
