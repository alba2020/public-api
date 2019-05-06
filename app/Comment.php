<?php

namespace App;

class Comment extends BaseModel {

    public function task() {
        return $this->belongsTo('App\Task');
    }
}
