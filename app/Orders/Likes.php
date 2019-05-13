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
//        dd($details);

        if (!isset($details->url)) {
            throw MissingParameterException::create(['text' => 'url']);
        }

        $scraper = app()->make(InstagramScraperService::class);
        $scraper->checkMediaURL($details->url);

        if (!isset($details->n)) {
            throw MissingParameterException::create(['text' => 'n']);
        }

        if ($details->n < 100) {
            throw BadParameterException::create([
                'text' => 'n must be >= 100'
            ]);
        }
    }

    public function run() {
        $this->toNakrutka($this->details['url'], $this->details['n']);
    }
}
