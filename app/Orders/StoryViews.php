<?php

namespace App\Orders;

use App\Exceptions\BadMediaTypeException;
use App\Exceptions\BadParameterException;
use App\Exceptions\MissingParameterException;
use App\Order;
use App\Services\InstagramScraperService;
use Tightenco\Parental\HasParent;

class StoryViews extends Order {

    use HasParent;

    public static function validate($details) {

//        if (!isset($details->link)) {
//            throw MissingParameterException::create(['text' => 'link missing']);
//        }
//
//        $scraper = resolve(InstagramScraperService::class);
//        $type = $scraper->getMediaType($details->link);
//
//        if ($type !== "video") { // image
//            throw BadMediaTypeException::create();
//        }
//
//        if (!isset($details->quantity)) {
//            throw MissingParameterException::create(['text' => 'quantity missing']);
//        }
//
//        if ($details->quantity < 100) {
//            throw BadParameterException::create([
//                'text' => 'quantity must be >= 100'
//            ]);
//        }
    }

    public static function getImg($link) {
        // return resolve(InstagramScraperService::class)->getMediaImg($link);
    }

    public static function getInstagramLogin($link) {
//        $scraper = resolve(InstagramScraperService::class);
//        return $scraper->getLoginByMedia($link);
    }

    public function run() {
//        $this->toNakrutka($this->link, $this->quantity);
    }
}
