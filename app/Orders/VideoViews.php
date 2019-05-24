<?php

namespace App\Orders;

use App\Exceptions\BadMediaTypeException;
use App\Order;
use App\Orders\Traits\DefaultPriceAndCost;
use App\Orders\Traits\DefaultRun;
use App\Orders\Traits\ImageFromMedia;
use App\Orders\Traits\LoginFromMedia;
use App\Services\InstagramScraperService;
use App\SMM;
use Tightenco\Parental\HasParent;

class VideoViews extends Order {

    use HasParent;
    use DefaultPriceAndCost, ImageFromMedia, LoginFromMedia, DefaultRun;

    public static function validate($details) {

        SMM::withMinQuantity100(SMM::withQuantity(SMM::withLink($details)));

        $scraper = resolve(InstagramScraperService::class);
        $type = $scraper->getMediaType($details->link);

        if ($type !== "video") { // image
            throw BadMediaTypeException::create();
        }
    }
}
