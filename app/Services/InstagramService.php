<?php

namespace App\Services;

use App\User;
use InstagramAPI\Instagram;

class InstagramService
{
    private $ig;

    public function __construct(User $user) {
        $debug = false;
        $truncatedDebug = false;
        $storageConfig = [];

        $ig = new Instagram($debug, $truncatedDebug, $storageConfig);

        try {
            // Will resume if a previous session exists.
            $ig->login($user->instagram_login, $user->instagram_password);

            $p = $user->instagramProxy;
            $proxy_string = "http://$p->login:$p->password@$p->ip:$p->port";
            $ig->setProxy($proxy_string);
        } catch (\Exception $e) {
            echo 'Exception class: ' . get_class($e) . "\n";
            echo 'Instagram service constructor error: ' . $e->getMessage() . "\n";
//            exit(0);
        }
        $this->ig = $ig;
    }

    public function getMediaId($url)
    {
        try {
            $api = file_get_contents("http://api.instagram.com/oembed?url=$url");
            $apiObj = json_decode($api, true);
            $media_id = $apiObj['media_id'];
        } catch (\Exception $e) {
            echo 'Exception class: ' . get_class($e) . "\n";
            echo 'Get media id error: ' . $e->getMessage() . "\n";
        }
        return $media_id;
    }

    public function like($url)
    {
        $mediaId = $this->getMediaId($url);
        try {
            $response = $this->ig->media->like($mediaId);
        } catch (\Exception $e) {
            echo 'Exception class: ' . get_class($e) . "\n";
            echo 'Instagram like error: ' . $e->getMessage() . "\n";
        }
    }

    public function unlike($url)
    {
        $mediaId = $this->getMediaId($url);
        try {
            $response = $this->ig->media->unlike($mediaId);
        } catch (\Exception $e) {
            echo 'Exception class: ' . get_class($e) . "\n";
            echo 'Instagram unlike error: ' . $e->getMessage() . "\n";
        }
    }
}
