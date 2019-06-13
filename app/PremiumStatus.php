<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PremiumStatus extends BaseModel
{

    protected $casts = [
        'online_support' => 'boolean',
        'friends_cashback' => 'boolean',
        'event_bonuses' => 'boolean',

        'discount' => 'array',

        'premium_services' => 'boolean',
        'bonus_five_percent' => 'boolean',
        'personal_manager' => 'boolean',
    ];

//    public function users() {
//        return $this->hasMany('App\User');
//    }
}
