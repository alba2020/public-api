<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Facades\Log;
use InstagramAPI\Instagram;

class InstagramService
{
    private $ig;

    public static $types = ['like', 'dislike', 'follow', 'unfollow', 'comment', 'uncomment'];

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

    public static function getMediaId($url)
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
        $mediaId = self::getMediaId($url);
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

    private function getUserId($url)
    {
       $login = explode('/', $url)[3];
       Log::info("get instagram login $login");

       $userId = $this->ig->people->getUserIdForName($login);
       Log::info("user id $userId");

       return $userId;
    }

    public function follow($url)
    {
        try {
            $userId = $this->getUserId($url);
        } catch (\Exception $e) {
            echo "Could not get userId\n";
            echo 'Exception class: ' . get_class($e) . "\n";
            echo 'Instagram follow error: ' . $e->getMessage() . "\n";
        }

        try {
            $this->ig->people->follow($userId);
        } catch (\Exception $e) {
            echo "Could not follow\n";
            echo 'Exception class: ' . get_class($e) . "\n";
            echo 'Instagram follow error: ' . $e->getMessage() . "\n";
        }
    }

    public function unfollow($url)
    {
        try {
            $userId = $this->getUserId($url);
            $this->ig->people->unfollow($userId);
        } catch (\Exception $e) {
            echo 'Exception class: ' . get_class($e) . "\n";
            echo 'Instagram unfollow error: ' . $e->getMessage() . "\n";
        }
    }

    public function comment($url, $commentText)
    {
        $mediaId = self::getMediaId($url);
        try {
            $response = $this->ig->media->comment($mediaId, $commentText);
//            $response->printJson();
            $commentId = $response->getComment()->getPk();
        } catch (\Exception $e) {
            echo 'Exception class: ' . get_class($e) . "\n";
            echo 'Instagram comment error: ' . $e->getMessage() . "\n";
        }
        return $commentId;
    }

    public function uncomment($url, $commentId)
    {
        $mediaId = self::getMediaId($url);
        try {
            $response = $this->ig->media->deleteComment($mediaId, $commentId);
        } catch (\Exception $e) {
            echo 'Exception class: ' . get_class($e) . "\n";
            echo 'Instagram uncomment error: ' . $e->getMessage() . "\n";
        }
        return $response;
    }
}
