<?php

namespace App\Orders;

use App\Exceptions\BadParameterException;
use App\Exceptions\MissingParameterException;
use App\Order;
use App\Services\InstagramScraperService;
use Symfony\Component\HttpFoundation\Response;
use Tightenco\Parental\HasParent;

class Likes extends Order {

    use HasParent;

    public static function validate($details) {

        if (!isset($details->link)) {
            throw MissingParameterException::create(['text' => 'link missing']);
        }

        $scraper = resolve(InstagramScraperService::class);
        $scraper->checkMediaURL($details->link);

        if (!isset($details->quantity)) {
            throw MissingParameterException::create(['text' => 'quantity missing']);
        }

        if ($details->quantity < 100) {
            throw BadParameterException::create([
                'text' => 'quantity must be >= 100'
            ]);
        }
    }

    public static function getImg($link) {
        $scraper = resolve(InstagramScraperService::class);
        return $scraper->getMediaImg($link);
    }

    public static function getInstagramLogin($link) {
        $scraper = resolve(InstagramScraperService::class);
        return $scraper->getLoginByMedia($link);
    }

    public function run() {
        $this->toNakrutka($this->link, $this->quantity);
    }
}
