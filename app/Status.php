<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    // jobs
    const CREATED = 'CREATED';
    const RUNNING = 'RUNNING';
    const COMPLETED = 'COMPLETED';
    const ERROR = 'ERROR';

    // bots
    const BOT_OK = 'BOT_OK';
    const BOT_ERROR = 'BOT_ERROR';
    const BOT_UNKNOWN = 'BOT_UNKNOWN';
}
