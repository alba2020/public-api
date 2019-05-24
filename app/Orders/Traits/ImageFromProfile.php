<?php

namespace App\Orders\Traits;

use App\Services\InstagramScraperService;

trait ImageFromProfile {

    public static function getImg($details) {
        $scraper = resolve(InstagramScraperService::class);
        return $scraper->getProfileImg($details['link']);
    }
}
