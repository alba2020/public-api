<?php

namespace App\Orders;

use App\Order;
use App\Orders\Traits\Subscriptions;
use Tightenco\Parental\HasParent;

class AutoLikes extends Order {

    use HasParent;
    use Subscriptions;
}
