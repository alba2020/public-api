<?php

namespace App\Services;

use InstagramAPI\Instagram;

class InstagramService
{
    private $ig;

    public function __construct($username, $password) {
        $debug = false;
        $truncatedDebug = false;
        $storageConfig = [];

        $ig = new Instagram($debug, $truncatedDebug, $storageConfig);

        try {
            // Will resume if a previous session exists.
            $ig->login($username, $password);
        } catch (\Exception $e) {
            echo 'Something went wrong: '.$e->getMessage()."\n";
            exit(0);
        }
        $this->ig = $ig;
    }

    public function getMediaId($url)
    {
        $api = file_get_contents("http://api.instagram.com/oembed?url=$url");
        $apiObj = json_decode($api, true);
        $media_id = $apiObj['media_id'];
        return $media_id;
    }

    public function like($url)
    {
        $mediaId = $this->getMediaId($url);
        $response = $this->ig->media->like($mediaId);
    }

    public function unlike($url)
    {
        $mediaId = $this->getMediaId($url);
        $response = $this->ig->media->unlike($mediaId);
    }

}
