<?php

namespace App;

use App\Exceptions\EntityNotFoundException;
use App\Exceptions\ForeignServiceException;
use App\Exceptions\InsufficientFundsException;
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

    protected $childTypes = Constants::subclasses;

    protected $casts = [
        'details' => 'array',
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
                'text' => 'Order not found by uuid'
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

    public static function getImg($details) {
        return '';
    }

    public static function getInstagramLogin($details) {
        return '';
    }

    public static function make($service, $user, $link, $quantity) {
        try {
            $order = static::create([
                'uuid' => self::makeUUID(),
                'user_id' => $user->id,
                'service_id' => $service->id,
//                'details' => (array) $details,
                'link' => $link,
                'quantity' => $quantity,
                'price' => $service->getPrice($quantity), // цена за 1 шт
                'cost' => $service->getCost($quantity), // общая стоимость
                'img' => static::getImg($link),
                'instagram_login' => static::getInstagramLogin($link),
            ]);

            $order->refresh(); // load defaults from db
        } catch (\Exception $e) {
            throw ServerException::create(['text' => $e->getMessage()]);
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

    public function toNakrutka($link, $quantity) {
        $nakrutka = resolve(NakrutkaService::class);
        $service = Service::findOrFail($this->service_id);
        $nakrutka->setApiService($service->nakrutka_id);

        $link = rand(1, 10000);
        $response = $nakrutka->add('http://[BAD_URL]' . $link, $quantity);
        if (!isset($response->order)) {
            throw ForeignServiceException::create(['text' => 'nakrutka did not return order']);
        }

        $this->foreign_id = $response->order;
        $this->status = Order::STATUS_RUNNING;
        $this->save();
    }

    // проверить статус заказа по ответу внешнего сервиса
    public function updateData($response) {
//        echo "update order id $this->id\n";

        $foreign_id = $this->foreign_id;
        $this->status = self::convertStatus($response->$foreign_id->status);
        $this->remains = $response->$foreign_id->remains;

//        echo "this->remains = $this->remains\n";

        if ($this->status === static::STATUS_PARTIAL_COMPLETED) {
            $refund = $this->remains * $this->price; // возврат

            $this->wallet->applyTransaction(
                Transaction::INFLOW_REFUND,
                $refund,
                "Order id: $this->id uuid: $this->uuid remains: $this->remains"
            );
        }
        $this->save();
    }

    public static function convertStatus($ns):string {
        $table = [
            'In progress' => static::STATUS_RUNNING,
            'Pending' => static::STATUS_RUNNING,
            'Processing' => static::STATUS_RUNNING,
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
