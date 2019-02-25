<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Facades\Auth;

class SMMAuthService
{
    public function loginWithEmail($email, $password)
    {
        $token = '';
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->accessToken;
        }

        return $token;
    }

    public function loginWithVK($vk_id, $vk_token)
    {
        $user = User::where('vk_id', $vk_id)->first();
        if (!$user) { // new vk user, create local user
            $user = User::create([ 'vk_id' => $vk_id ]);
        }
        $user->vk_token = $vk_token;
        $user->save();

        $token = $user->createToken('MyApp')->accessToken;
        return $token;
    }

    public function loginWithFB($fb_id, $fb_token)
    {
        $user = User::where('fb_id', $fb_id)->first();
        if (!$user) { // new fb user, create local user
            $user = User::create(['fb_id' => $fb_id]);
        }
        $user->fb_token = $fb_token;
        $user->save();

        $token = $user->createToken('MyApp')->accessToken;
        return $token;
    }
}
