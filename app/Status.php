<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    // jobs
    const CREATED = 1;
    const RUNNING = 2;
    const COMPLETED = 4;
    const ERROR = 8;

    // bots
    const BOT_OK = 16;
    const BOT_ERROR = 32;
}
