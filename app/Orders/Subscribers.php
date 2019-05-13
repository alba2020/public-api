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
        if (isset($details->instagram_login)) {
            $login = $details->instagram_login;
            if (strpos($login, 'instagram.com') !== false) {
                $tokens = explode('/', $login);
                $details->instagram_login = $tokens[3];
            }
        }
    }

    public static function validate($details) {
//        dd($details);

        if (!isset($details->instagram_login)) {
            throw MissingParameterException::create(['text' => 'url']);
        }

        $scraper = app()->make(InstagramScraperService::class);
        $scraper->checkLogin($details->instagram_login);

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
        $this->toNakrutka($this->details['instagram_login'], $this->details['n']);
    }
}
