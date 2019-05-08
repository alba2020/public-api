<?php

namespace App;

use App\Exceptions\EntityNotFoundException;
use App\Exceptions\InsufficientFundsException;
use Tightenco\Parental\HasChildren;

class Order extends BaseModel {

    use HasChildren;

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
}
