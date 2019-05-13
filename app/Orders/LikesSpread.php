<?php

namespace App\Orders;

use App\Exceptions\BadParameterException;
use App\Exceptions\MissingParameterException;
use App\Exceptions\ServerException;
use App\Order;
use App\Service;
use App\Services\InstagramScraperService;
use App\Services\NakrutkaService;
use Symfony\Component\HttpFoundation\Response;
use Tightenco\Parental\HasParent;

class LikesSpread extends Order {

    use HasParent;

    public static function validate($details) {
        if (!isset($details->instagram_login)) {
            throw MissingParameterException::create(['text' => 'instagram_login']);
        }

        $scraper = app()->make(InstagramScraperService::class);
        $scraper->checkLoginNotPrivate($details->instagram_login);

        if (!isset($details->likes_per_post)) {
            throw MissingParameterException::create(['text' => 'likes_per_post']);
        }

        if ($details->likes_per_post < 100) {
            throw BadParameterException::create([
                'text' => 'likes_per_post must be >= 100'
            ]);
        }

        if (!isset($details->posts)) {
            throw MissingParameterException::create(['text' => 'posts']);
        }

        $scraper->checkNumberOfPosts($details->instagram_login, $details->posts);
    }

    public static function make($service, $user, $details) {
        $cost = $service->getCost($details->likes_per_post * $details->posts);

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

        $scraper = app()->make(InstagramScraperService::class);
        $codes = $scraper->getMediaCodes(
            $this->details['instagram_login'],
            $this->details['posts']
        );

        $ids = [];
        foreach($codes as $code) {
            $response = $nakrutka->add(
                'https://[BAD_URL]www.instagram.com/p/' . $code,
                $this->details['likes_per_post']
            );
            $ids[] = $response->order;
        }

        $this->details = $this->details + ['nakrutka_ids' => $ids];
        $this->status = Order::STATUS_RUNNING;
        $this->save();

        return response()->json($this, Response::HTTP_CREATED);
    }
}
