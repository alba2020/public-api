<?php

namespace App\Services;

use GuzzleHttp\Client;

class FBService
{
    private $client_id, $secret, $redirect_uri;
    private $httpClient;

    public function __construct($config) {
        $this->client_id = $config['client_id'];
        $this->secret = $config['secret'];
        $this->redirect_uri = $config['redirect_uri'];

        $this->httpClient = new Client();
    }

    public function getToken($code)
    {
        $fb_token = $this->httpClient
            ->get('https://graph.facebook.com/v3.2/oauth/access_token',
                [
                    'query' => [
                        'client_id' => $this->client_id,
                        'redirect_uri' => $this->redirect_uri,
                        'client_secret' => $this->secret,
                        'code' => $code,
                    ]
                ]
            )->getBody()->getContents();

        return json_decode($fb_token);
    }

    public function confirmToken($input_token, $access_token)
    {
        $data = $this->httpClient
            ->get('https://graph.facebook.com/debug_token',
                [
                    'query' => [
                        'input_token' => $input_token,
                        'access_token' => $access_token,
                    ]
                ]
            )->getBody()->getContents();
        return json_decode($data);
    }
}
