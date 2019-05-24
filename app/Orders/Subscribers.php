<?php

namespace App\Orders;

use App\Order;
use App\Orders\Traits\DefaultPriceAndCost;
use App\Orders\Traits\DefaultRun;
use App\Orders\Traits\ImageFromProfile;
use App\Orders\Traits\LoginFromLink;
use App\Services\InstagramScraperService;
use App\SMM;
use Tightenco\Parental\HasParent;

class Subscribers extends Order {

    use HasParent;
    use DefaultPriceAndCost, ImageFromProfile, LoginFromLink, DefaultRun;

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

        $scraper = resolve(InstagramScraperService::class);
        $scraper->checkLogin($details->link);
    }
}
