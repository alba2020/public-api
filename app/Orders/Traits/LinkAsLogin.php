<?php

namespace App\Orders\Traits;


use App\Services\InstagramScraperService;
use App\SMM;

trait LinkAsLogin {

    public static function convert($details) {
        if (isset($details->link)) {
            $login = $details->link;
            if (strpos($login, 'instagram.com') !== false) {
                $tokens = explode('/', $login);
                $details->link = $tokens[3];
            }
        }
    }

    public static function validate($details) {

        SMM::withMinQuantity100(SMM::withQuantity(SMM::withLink($details)));

        resolve(InstagramScraperService::class)->checkLogin($details->link);
    }

    public static function getImg($details) {
        $scraper = resolve(InstagramScraperService::class);
        return $scraper->getProfileImg($details['link']);
    }

    public static function getInstagramLogin($details) {
        return $details['link'];
    }
}
