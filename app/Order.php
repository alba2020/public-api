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

    public static function make($service, $user, $details) {
        $cost = $service->getCost($details->n);

        try {
            $order = static::create([
                'uuid' => md5(uniqid()),
                'user_id' => $user->id,
                'service_id' => $service->id,
                'details' => (array) $details,
                'cost' => $cost,
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
        $nakrutka = app()->make(NakrutkaService::class);
        $service = Service::findOrFail($this->service_id);
        $nakrutka->setApiService($service->nakrutka_id);

        $response = $nakrutka->add('http://[BAD_URL]' . $link, $quantity);
        if (!isset($response->order)) {
            throw ForeignServiceException::create(['text' => 'nakrutka did not return order']);
        }

        $this->details = $this->details + ['nakrutka_id' => $response->order];
        $this->status = Order::STATUS_RUNNING;
        $this->save();
    }

    // получить id заказа во внешнем сервисе
    public function getForeignId() {
        return $this->details['nakrutka_id'];
    }

    // проверить статус заказа по ответу внешнего сервиса
    public function changeStatus($response) {
        $nid = $this->getForeignId();
        $this->status = self::convertStatus($response->$nid->status);
        $this->save();
    }

    public static function convertStatus($ns):string {
        $table = [
            'In progress' => static::STATUS_RUNNING,
            'Pending' => static::STATUS_RUNNING,
            'Partial' => static::STATUS_RUNNING,
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
