<?php

namespace App\Orders;

use App\Order;
use App\Orders\Traits\DefaultPriceAndCost;
use App\Orders\Traits\DefaultRun;
use App\Orders\Traits\LinkAsLogin;
use Tightenco\Parental\HasParent;

class ViewsLive extends Order {

    use HasParent;
    use DefaultPriceAndCost, LinkAsLogin, DefaultRun;
}
