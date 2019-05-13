<?php

namespace App;

class Action extends BaseModel {

    public function task() {
        return $this->belongsTo('App\Task');
    }

    public function worker() {
        return $this->belongsTo('App\User', 'worker_id');
    }
}
