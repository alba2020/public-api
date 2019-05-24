<?php

namespace App\Orders\Traits;

use App\Services\InstagramScraperService;

trait LoginFromMedia {

    public static function getInstagramLogin($details) {
        $scraper = resolve(InstagramScraperService::class);
        return $scraper->getLoginByMedia($details['link']);
    }
}
