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

//        Быстрые лайки - like
//        Просмотры видео - media-player
//        Просмотры видео с охватом - videos-coverage
//        Просмотры Story - story
//        Подписчики высокого качества - subs
//        Подписчики с автовосстановлением - network
//        Автолайки - autolike
//        Автопросмотры - autowatch
//        Автолайки + автопросмотры + охват - advertising

        Service::create([
            'type' => Constants::SERVICE_INSTAGRAM_LIKES,
            'title' => 'Лайки',
            'startup' => 'до 5 минут',
            'speed' => '500-1000 в минуту',
            'details' => 'Качественные лайки',
            'requirements' => 'Аккаунт должен быть открытым',
            'info' => ['информация 1', 'информация 2'],
            'min' => 100,
            'max' => 10000,
            'nakrutka_id' => 55,
            'price' => 0.05,
            'price_1k' => 0.045,
            'price_5k' => 0.042,
            'price_10k' => 0.039,
            'price_25k' => 0.038,
            'price_50k' => 0.035,
            'price_100k' => 0.035,
            'img' => '/svg/like.svg',
        ]);

        Service::create([
            'type' => Constants::SERVICE_INSTAGRAM_SUBS,
            'title' => 'Подписчики',
            'startup' => 'до 5 минут',
            'speed' => '500-1000 в минуту',
            'details' => 'Качественные подписчики',
            'requirements' => 'Аккаунт должен быть открытым',
            'info' => ['информация 1', 'информация 2'],
            'min' => 100,
            'max' => 10000,
            'nakrutka_id' => 55,
            'price' => 0.35,
            'price_1k' => 0.3,
            'price_5k' => 0.278,
            'price_10k' => 0.27,
            'price_25k' => 0.25,
            'price_50k' => 0.198,
            'price_100k' => 0.17,
            'img' => '/svg/subs.svg',
        ]);

        Service::create([
            'type' => Constants::SERVICE_AUTO_FAKE,
            'title' => 'Вымышленный сервис AUTO FAKE',
            'startup' => 'до 5 минут',
            'speed' => '500-1000 в минуту',
            'details' => 'Не использовать',
            'requirements' => 'Аккаунт не должен быть',
            'info' => ['информация 1', 'информация 2'],
            'min' => 100,
            'max' => 10000,
            'nakrutka_id' => 55,
            'price' => 0.35,
            'price_1k' => 0.3,
            'price_5k' => 0.278,
            'price_10k' => 0.27,
            'price_25k' => 0.25,
            'price_50k' => 0.198,
            'price_100k' => 0.17,
        ]);

        Service::create([
            'type' => Constants::SERVICE_INSTAGRAM_VIDEO_VIEWS,
            'title' => 'Просмотры видео',
            'startup' => 'до 5 минут',
            'speed' => '500-1000 в минуту',
            'details' => 'Просмотры видео',
            'requirements' => 'Аккаунт должен быть',
            'info' => ['информация 1', 'информация 2'],
            'min' => 100,
            'max' => 10000,
            'nakrutka_id' => 55,
            'price' => 0.04,
            'price_1k' => 0.035,
            'price_5k' => 0.033,
            'price_10k' => 0.03,
            'price_25k' => 0.03,
            'price_50k' => 0.026,
            'price_100k' => 0.025,
            'img' => '/svg/media-player.svg',
        ]);

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
