<?php

namespace App;

use Illuminate\Support\Facades\Cache;

class Cached {

    protected $instance;
    protected $timeToLive;

    public function __construct($instance) {
        $this->instance = $instance;
        $this->timeToLive = 5; // min
    }

    public function __call($method, $args)
    {
        $key = "_" . $method;

        foreach ($args as $arg) {
            $key = $key . "_" . $arg;
        }

        if (Cache::has($key)) {
            return Cache::get($key);
        } else {
            $result = call_user_func_array([$this->instance, $method], $args);
            Cache::put($key, $result, $this->timeToLive);
            return $result;
        }
    }
}
