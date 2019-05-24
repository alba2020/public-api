<?php

namespace App\Orders\Traits;

trait LoginFromLink {

    public static function getInstagramLogin($details) {
        return $details['link'];
    }
}
