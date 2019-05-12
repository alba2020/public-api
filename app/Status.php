<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    // bots
    const BOT_OK = 'BOT_OK';
    const BOT_ERROR = 'BOT_ERROR';
    const BOT_UNKNOWN = 'BOT_UNKNOWN';
}
