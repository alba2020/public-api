<?php

namespace App\Services;

use GuzzleHttp\Client;


class VKService
{
    public function getToken($code)
    {
        $client_id = "6846339";
        $secret = "zEOI50HlqbDQwoGpiJHV";
        $redirect_uri = "http://localhost:3000/login";

//        $url = "?client_id=" . $client_id;
//        $url .= "&client_secret=" . $secret;
//        $url .= "&redirect_uri=" . $redirect_uri;
//        $url .= "&code=" . $code;

        $client = new Client();

        try {
            $vk_response = $client->get('https://oauth.vk.com/access_token',
                [
                    'query' => [
                        'client_id' => $client_id,
                        'client_secret' => $secret,
                        'redirect_uri' => $redirect_uri,
                        'code' => $code,
                    ]
                ]
            )->getBody()->getContents();

        } catch (GuzzleException $ex) {
//            return response()->json([
//                'message' => 'guzzle error'
//            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $vk_response = json_decode($vk_response);
        return $vk_response;
    }
}
