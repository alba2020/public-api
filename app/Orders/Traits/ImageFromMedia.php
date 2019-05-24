<?php

namespace App\Orders\Traits;

use App\Services\InstagramScraperService;

trait ImageFromMedia {

    public static function getImg($details) {
        $scraper = resolve(InstagramScraperService::class);
        return $scraper->getMediaImg($details['link']);
    }
}
