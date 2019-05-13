<?php

use App\Constants;
use App\Service;
use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Service::create([
            'id' => 1,
            'type' => Constants::SERVICE_INSTAGRAM_LIKES,
            'title' => 'Лайки',
            'startup' => 'до 5 минут',
            'speed' => '500-1000 в минуту',
            'details' => 'Качественные лайки',
            'requirements' => 'Аккаунт должен быть открытым',
            'min' => 100,
            'max' => 10000,
            'nakrutka_id' => 1,
            'price' => 0.05,
            'price_1k' => 0.045,
            'price_5k' => 0.042,
            'price_10k' => 0.039,
            'price_25k' => 0.038,
            'price_50k' => 0.035,
            'price_100k' => 0.035,
        ]);

//        Service::create([
//            'id' => 2,
//            'type' => Constants::SERVICE_INSTAGRAM_LIKES_SPREAD,
//            'title' => 'Лайки на несколько публикаций',
//            'startup' => 'до 5 минут',
//            'speed' => '500-1000 в минуту',
//            'min' => 100,
//            'max' => 10000,
//            'nakrutka_id' => 1,
//            'price' => 0.05,
//            'price_1k' => 0.045,
//            'price_5k' => 0.042,
//            'price_10k' => 0.039,
//            'price_25k' => 0.038,
//            'price_50k' => 0.035,
//            'price_100k' => 0.035,
//        ]);


        Service::create([
            'id' => 2,
            'type' => Constants::SERVICE_INSTAGRAM_SUBS,
            'title' => 'Подписчики',
            'startup' => 'до 5 минут',
            'speed' => '500-1000 в минуту',
            'details' => 'Качественные подписчики',
            'requirements' => 'Аккаунт должен быть открытым',
            'min' => 100,
            'max' => 10000,
            'nakrutka_id' => 1,
            'price' => 0.35,
            'price_1k' => 0.3,
            'price_5k' => 0.278,
            'price_10k' => 0.27,
            'price_25k' => 0.25,
            'price_50k' => 0.198,
            'price_100k' => 0.17,
        ]);

//        Service::create([
//            'id' => 3,
//            'type' => Constants::SERVICE_INSTAGRAM_VIEWS,
//            'title' => 'Просмотры',
//            'startup' => 'до 5 минут',
//            'speed' => '500-1000 в минуту',
//            'min' => 100,
//            'max' => 10000,
//            'nakrutka_id' => 1,
//            'price' => 0.04,
//            'price_1k' => 0.035,
//            'price_5k' => 0.033,
//            'price_10k' => 0.03,
//            'price_25k' => 0.03,
//            'price_50k' => 0.026,
//            'price_100k' => 0.025,
//        ]);
//
//        Service::create([
//            'id' => 4,
//            'type' => Constants::SERVICE_INSTAGRAM_AUTOLIKES,
//            'title' => 'Автолайки',
//            'startup' => 'до 5 минут',
//            'speed' => '500-1000 в минуту',
//            'min' => 100,
//            'max' => 10000,
//            'nakrutka_id' => 1,
//            'price' => 0.04,
//            'price_1k' => 0.035,
//            'price_5k' => 0.033,
//            'price_10k' => 0.03,
//            'price_25k' => 0.03,
//            'price_50k' => 0.026,
//            'price_100k' => 0.025,
//        ]);
//
//        Service::create([
//            'id' => 5,
//            'type' => Constants::SERVICE_INSTAGRAM_AUTOVIEWS,
//            'title' => 'Автопросмотры',
//            'startup' => 'до 5 минут',
//            'speed' => '500-1000 в минуту',
//            'min' => 100,
//            'max' => 10000,
//            'nakrutka_id' => 1,
//            'price' => 0.05,
//            'price_1k' => 0.045,
//            'price_5k' => 0.04,
//            'price_10k' => 0.039,
//            'price_25k' => 0.038,
//            'price_50k' => 0.035,
//            'price_100k' => 0.035,
//        ]);
//
//        Service::create([
//            'id' => 6,
//            'type' => Constants::SERVICE_INSTAGRAM_STORIES_VIEWS,
//            'title' => 'Stories Views',
//            'startup' => 'до 5 минут',
//            'speed' => '500-1000 в минуту',
//            'min' => 100,
//            'max' => 10000,
//            'nakrutka_id' => 1,
//            'price' => 0.11,
//            'price_1k' => 0.1,
//            'price_5k' => 0.09,
//            'price_10k' => 0.09,
//            'price_25k' => 0.09,
//            'price_50k' => 0.09,
//            'price_100k' => 0.09,
//        ]);
    }
}
