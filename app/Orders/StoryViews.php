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

        // link == login

        if (!isset($details->link)) {
            throw MissingParameterException::create(['text' => 'link missing']);
        }

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
        return resolve(InstagramScraperService::class)->getProfileImg($link);
    }

    public static function getInstagramLogin($link) {
        return $link;
    }

    public function run() {
        $this->toNakrutka($this->link, $this->quantity);
    }
}
