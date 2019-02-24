<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    const CREATED = 1;
    const RUNNING = 2;
    const COMPLETED = 4;
    const ERROR = 8;
}
