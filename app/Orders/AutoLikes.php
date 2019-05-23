<?php

namespace App\Orders;

use App\Exceptions\MissingParameterException;
use Tightenco\Parental\HasParent;

class AutoLikes {
    use HasParent;

    public static function validate($details) {

        if (!isset($details->username)) {
            throw MissingParameterException::create(['text' => 'username missing']);
        }

        if (!isset($details->qmin)) {
            throw MissingParameterException::create(['text' => 'qmin missing']);
        }

        if (!isset($details->qmax)) {
            throw MissingParameterException::create(['text' => 'qmax missing']);
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
