<?php

namespace App;

use App\Exceptions\EntityNotFoundException;
use App\Exceptions\ForeignServiceException;
use App\Exceptions\InsufficientFundsException;
use App\Exceptions\MissingParameterException;
use App\Exceptions\ServerException;
use App\Services\NakrutkaService;
use Tightenco\Parental\HasChildren;

class Order extends BaseModel {

    use HasChildren;

    const STATUS_CREATED = 'STATUS_CREATED';
    const STATUS_RUNNING = 'STATUS_RUNNING';
    const STATUS_PARTIAL_COMPLETED = 'STATUS_PARTIAL_COMPLETED';
    const STATUS_COMPLETED = 'STATUS_COMPLETED';
    const STATUS_ERROR = 'STATUS_ERROR';
    const STATUS_CANCELED = 'STATUS_CANCELED';
    const STATUS_UNKNOWN = 'STATUS_UNKNOWN';
    const STATUS_PAUSED = 'STATUS_PAUSED';

    protected $childTypes = Constants::subclasses;

    protected $casts = [
        'details' => 'array',
        'foreign_status' => 'array',
        'paid' => 'boolean',
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function service() {
        return $this->belongsTo('App\Service');
    }

    public static function byUUID(string $uuid) {
        $order = Order::where('uuid', $uuid)->first();
        if (!$order) {
            throw EntityNotFoundException::create([
                'text' => "Order not found by uuid $uuid",
            ]);
        }
        return $order;
    }

    public static function convert($details) {
        return;
    }

    public static function validate($details) {
        return true;
    }

    public static function makeUUID() {
        return md5(uniqid());
    }

    public static function getPrice($service, $details) {
        return 0;
    }

    public static function getCost($service, $details) {
        return 0;
    }

    public static function getImg($details) {
        return '';
    }

    public static function getInstagramLogin($details) {
        return '';
    }

    public static function make($service, $user, $details) {

// link, quantity,
// or
// username, min, max, posts, delay

        try {
            $order = static::create([
                'uuid' => self::makeUUID(),
                'user_id' => $user->id,
                'service_id' => $service->id,
                'details' => (array) $details,

//                'link' => $link,
//                'quantity' => $quantity,
//                'price' => $service->getPrice($quantity), // цена за 1 шт
//                'cost' => $service->getCost($quantity), // общая стоимость
//                'img' => static::getImg($link),
//                'instagram_login' => static::getInstagramLogin($link),
                'price' => static::getPrice($service, $details),
                'cost' => static::getCost($service, $details),
                'img' => static::getImg($details),
                'instagram_login' => static::getInstagramLogin($details),
            ]);

            $order->refresh(); // load defaults from db
        } catch (\Exception $e) {
            throw ServerException::create([
                'text' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }

        return $order;
    }

    public function pay() {
        $wallet = $this->user->wallet;

        if ($wallet->balance < $this->cost) {
            throw InsufficientFundsException::create();
        }

        $wallet->applyTransaction(
            Transaction::OUTFLOW_ORDER,
            (-1) * $this->cost,
            "Order id: $this->id uuid: $this->uuid"
        );

        $this->paid = true;
        $this->save();
    }

    // проверить статус заказа по ответу внешнего сервиса
    public function updateData($response) {
//        echo "update order id $this->id\n";

        $foreign_id = $this->foreign_id;
        $this->status = self::convertStatus($response->$foreign_id->status);

        if (isset($response->$foreign_id->remains)) { // default
            $remains = $response->$foreign_id->remains;
            $incomplete = $remains / $this->details['quantity'];
            $refund = $incomplete * $this->cost; // возврат
            $refund = round($refund, 2, PHP_ROUND_HALF_DOWN);
        } else if (isset($response->$foreign_id->posts)) { // subscriptions
            $total_posts = $this->details['posts'];
            $completed_posts = $response->$foreign_id->posts;
            $incomplete = ($total_posts - $completed_posts) / $total_posts;
            $refund = $this->cost * $incomplete;
            $refund = round($refund, 2, PHP_ROUND_HALF_DOWN);
        }

//        $this->remains = $response->$foreign_id->remains;
//        echo "this->remains = $this->remains\n";

        if ($this->status === static::STATUS_PARTIAL_COMPLETED) {
            $this->wallet->applyTransaction(
                Transaction::INFLOW_REFUND,
                $refund,
                "Order id: $this->id uuid: $this->uuid cost: $this->cost refund: $refund"
            );
        }

        $this->foreign_status = (array) $response->$foreign_id;
        $this->save();
    }

    public static function convertStatus($ns):string {
        $table = [
            'In progress' => static::STATUS_RUNNING,
            'Pending' => static::STATUS_RUNNING,
            'Processing' => static::STATUS_RUNNING,

            'Active' => static::STATUS_RUNNING,
            'Paused' => static::STATUS_PAUSED,

            'Partial' => static::STATUS_PARTIAL_COMPLETED,
            'Canceled' => static::STATUS_CANCELED,
            'Completed' => static::STATUS_COMPLETED,
        ];

        if (array_key_exists($ns, $table)) {
            return $table[$ns];
        } else {
            return static::STATUS_UNKNOWN;
        }
    }
}
