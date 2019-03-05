<?php

namespace App\Services;

use App\Fake;
use App\User;

class FakeService
{
    public static $types = ['like', 'unlike'];

    public function __construct() {

    }

    public function like(User $user, $url) {
        $id = $user->id;
        echo "*** Fake Service: user $id likes $url ***\n";
        $fake = Fake::where('url', $url)->first();
        if (!$fake->likes) {
            $fake->likes = '' . $id;
        } else {
            $fake->likes = $fake->likes . ' ' . $id;
        }
        $fake->save();
    }

    public function unlike(User $user, $url) {
        $id = $user->id;
        echo "*** Fake Service: user $id unlikes $url ***\n";
        $fake = Fake::where('url', $url)->first();
        $likesArray = explode(' ', $fake->likes);
        $likesArray = array_diff($likesArray, [$id]);
        $fake->likes = join(' ', $likesArray);
        $fake->save();
    }
}
