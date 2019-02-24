<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $guarded = [];

    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    public function worker()
    {
        return $this->belongsTo('App\User', 'worker_id');
    }
}
