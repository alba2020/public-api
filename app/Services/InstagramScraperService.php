<?php

namespace App\Services;

use App\Exceptions\BadParameterException;
use App\Exceptions\NotEnoughMediaException;
use App\Exceptions\PrivateAccountException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use InstagramAPI\Exception\InstagramException;
use \InstagramScraper\Instagram;

class InstagramScraperService {

    protected $instagram;

    public function __construct() {
        $this->instagram = new Instagram(); // no login
    }

    public function checkMediaURL($url) {
        try {
            $media = $this->instagram->getMediaByUrl($url);
        } catch (\Exception $e) {
            throw BadParameterException::create([
                'text' => 'bad url',
                'info' => $e->getMessage(),
            ]);
        }

        return true;
    }

    public function checkLogin($login) {
        try {
            $account = $this->instagram->getAccount($login);
        } catch (\Exception $e) {
            throw BadParameterException::create([
                'text' => 'bad login',
                'info' => $e->getMessage(),
            ]);
        }

        return true;
    }

    public function checkLoginNotPrivate($login) {
        try {
            $account = $this->instagram->getAccount($login);
        } catch (\Exception $e) {
            throw BadParameterException::create([
                'text' => 'bad login',
                'info' => $e->getMessage(),
            ]);
        }

        if($account->isPrivate()) {
            throw PrivateAccountException::create(['text' => $login]);
        }

        return true;
    }

    public function checkNumberOfPosts($login, $amount) {
        $medias = $this->instagram->getMedias($login, $amount);
        if(count($medias) < $amount) {
            throw NotEnoughMediaException::create([
                'amount' => $amount,
                'actual' => count($medias),
            ]);
        }
        return true;
    }

    public function getMediaCodes($login, $amount) {
        $medias = $this->instagram->getMedias($login, $amount);
        return array_map(function($media) {
            return $media->getShortCode();
        }, $medias);
    }

    public function getMediaURLs($login, $amount) {
        $medias = $this->instagram->getMedias($login, $amount);

        $urls = array_map(function($media) {
            $url = 'https://www.instagram.com/p/' . $media->getShortCode();
            return $url;
        }, $medias);

        return $urls;
    }

    public function getMediaImg($url) {
        $media = $this->instagram->getMediaByUrl($url);
        $imageUrl = $media->getImageThumbnailUrl();
        return $imageUrl;
    }

    public function getProfileImg($login) {
        $account = $this->instagram->getAccount($login);
        $avatar = $account->getProfilePicUrl();
        return $avatar;
    }

    public function getLoginByMedia($url) {
        $media = $this->instagram->getMediaByUrl($url);
        $username = $media->getOwner()->getUsername();
        return $username;
    }

    public function getMediaType($url) {
        try {
            $media = $this->instagram->getMediaByUrl($url);
            $type = $media->getType();
        } catch (\Exception $e) {
            throw BadParameterException::create([
                'text' => 'bad media url',
                'info' => $e->getMessage(),
            ]);
        }

        return $type;
    }

    public function double($n) {
        sleep(2);
        return $n * 2;
    }

    public function triple($n) {
        sleep(3);
        return $n * 3;
    }
}
