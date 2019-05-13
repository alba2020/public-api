<?php

namespace App\Services;

use App\Exceptions\BadParameterException;
use App\Exceptions\NotEnoughMediaException;
use App\Exceptions\PrivateAccountException;
use \InstagramScraper\Instagram;

class InstagramScraperService {

    public function checkMediaURL($url) {
        $instagram = new Instagram(); // no login

        try {
            $media = $instagram->getMediaByUrl($url);
        } catch (\Exception $e) {
            throw BadParameterException::create([
                'text' => 'bad url',
                'info' => $e->getMessage(),
            ]);
        }

        return true;
    }

    public function checkLogin($login) {
        $instagram = new Instagram();

        try {
            $account = $instagram->getAccount($login);
        } catch (\Exception $e) {
            throw BadParameterException::create([
                'text' => 'bad login',
                'info' => $e->getMessage(),
            ]);
        }
    }

    public function checkLoginNotPrivate($login) {
        $instagram = new Instagram();

        try {
            $account = $instagram->getAccount($login);
        } catch (\Exception $e) {
            throw BadParameterException::create([
                'text' => 'bad login',
                'info' => $e->getMessage(),
            ]);
        }

        if($account->isPrivate()) {
            throw PrivateAccountException::create(['text' => $login]);
        }
    }

    public function checkNumberOfPosts($login, $amount) {
        $instagram = new Instagram();
        $medias = $instagram->getMedias($login, $amount);
        if(count($medias) < $amount) {
            throw NotEnoughMediaException::create([
                'amount' => $amount,
                'actual' => count($medias),
            ]);
        }
    }

    public function getMediaCodes($login, $amount) {
        $instagram = new Instagram();
        $medias = $instagram->getMedias($login, $amount);

        return array_map(function($media) {
            return $media->getShortCode();
        }, $medias);
    }
}
