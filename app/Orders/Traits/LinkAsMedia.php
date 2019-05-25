<?php

namespace App\Orders\Traits;


use App\Services\InstagramScraperService;
use App\SMM;

trait LinkAsMedia {

    public static function validate($details) {

        SMM::withMinQuantity100(SMM::withQuantity(SMM::withLink($details)));

        resolve(InstagramScraperService::class)->checkMediaURL($details->link);
    }

    public static function getImg($details) {
        $scraper = resolve(InstagramScraperService::class);
        return $scraper->getMediaImg($details['link']);
    }

    public static function getInstagramLogin($details) {
        $scraper = resolve(InstagramScraperService::class);
        return $scraper->getLoginByMedia($details['link']);
    }
}
