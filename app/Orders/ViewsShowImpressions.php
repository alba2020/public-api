<?php

namespace App\Orders;

use App\Order;
use App\Orders\Traits\DefaultPriceAndCost;
use App\Orders\Traits\DefaultRun;
use App\Orders\Traits\LinkAsMedia;
use Tightenco\Parental\HasParent;

class ViewsShowImpressions extends Order {

    use HasParent;
    use DefaultPriceAndCost, LinkAsMedia, DefaultRun;
}
