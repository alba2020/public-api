<?php

namespace App\Orders;

use App\Exceptions\BadParameterException;
use App\Exceptions\MissingParameterException;
use App\Exceptions\ServerException;
use App\Order;
use App\Service;
use App\Services\InstagramScraperService;
use App\Services\NakrutkaService;
use App\Status;
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

    public static function make($service, $user, $details) {
        $cost = $service->getCost($details->n);

        try {
            $order = static::create([
                'uuid' => md5(uniqid()),
                'user_id' => $user->id,
                'service_id' => $details->service_id,
                'details' => (array) $details,
                'cost' => $cost,
            ]);

            $order->refresh(); // load defaults from db
        } catch (\Exception $e) {
            throw ServerException::create(['text' => $e->getMessage()]);
        }

        return $order;
    }

    public function run() {
        $nakrutka = app()->make(NakrutkaService::class);

        $service = Service::findOrFail($this->service_id);
        $nakrutka->setApiService($service->nakrutka_id);
        $nakrutka->add('ftp://bad_url' . $this->details['url'], $this->details['n']);

        $this->status = Status::RUNNING;
        $this->save();

        return response()->json($this, Response::HTTP_CREATED);
    }
}
