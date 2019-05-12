<?php

namespace App;

class Transaction extends BaseModel {

    const INFLOW_TEST = 'INFLOW_TEST';
    const INFLOW_OTHER = 'INFLOW_OTHER';
    const INFLOW_CREATE = 'INFLOW_CREATE';

    const OUTFLOW_TEST = 'OUTFLOW_TEST';
    const OUTFLOW_OTHER = 'OUTFLOW_OTHER';
    const OUTFLOW_ORDER = 'OUTFLOW_ORDER';

    public function wallet() {
        return $this->belongsTo('App\Wallet');
    }
}
