<?php

namespace App\Orders;

use App\Exceptions\BadParameterException;
use App\Exceptions\MissingParameterException;
use App\Order;
use App\Services\InstagramScraperService;
use Symfony\Component\HttpFoundation\Response;
use Tightenco\Parental\HasParent;

class Subscribers extends Order {

    use HasParent;

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

        if (!isset($details->link)) {
            throw MissingParameterException::create(['text' => 'link missing']);
        }

        $scraper = resolve(InstagramScraperService::class);
        $scraper->checkLogin($details->link);

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
        return $scraper->getProfileImg($link);
    }

    public static function getInstagramLogin($link) {
        return $link;
    }

    public function run() {
        $this->toNakrutka($this->link, $this->quantity);
    }
}
