<?php

namespace App\Services;

use App\Exceptions\BadParameterException;
use App\Exceptions\NotEnoughMediaException;
use App\Exceptions\PrivateAccountException;
use Illuminate\Support\Facades\Cache;
use \InstagramScraper\Instagram;

class InstagramScraperService {

    protected $time = 30; // min

    public function checkMediaURL($url) {
        $key = __FUNCTION__ . $url;

        if (Cache::has($key)) {
            return true;
        }

        $instagram = new Instagram(); // no login
        try {
            $media = $instagram->getMediaByUrl($url);
            Cache::put($key, true, $this->time);
        } catch (\Exception $e) {
            throw BadParameterException::create([
                'text' => 'bad url',
                'info' => $e->getMessage(),
            ]);
        }

        return true;
    }

    public function checkLogin($login) {
        $key = __FUNCTION__ . $login;

        if (Cache::has($key)) {
            return true;
        }

        $instagram = new Instagram();
        try {
            $account = $instagram->getAccount($login);
            Cache::put($key, true, $this->time);
        } catch (\Exception $e) {
            throw BadParameterException::create([
                'text' => 'bad login',
                'info' => $e->getMessage(),
            ]);
        }
    }

    public function checkLoginNotPrivate($login) {
        $key = __FUNCTION__ . $login;
        $instagram = new Instagram();

        if (Cache::has($key)) {
            return true;
        }

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

        Cache::put($key, true, $this->time);
        return true;
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

    public function getMediaURLs($login, $amount) {
        $instagram = new Instagram();
        $medias = $instagram->getMedias($login, $amount);

        $urls = array_map(function($media) {
            $url = 'https://www.instagram.com/p/' . $media->getShortCode();
            $key = 'checkMediaURL' . $url;
            Cache::put($key, true, $this->time);
            return $url;
        }, $medias);

        return $urls;
    }
}
