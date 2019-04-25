<?php

namespace App\Services;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;

class VKService
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
        try {
            $vk_response = $this->httpClient
                ->get('https://oauth.vk.com/access_token',
                    [
                        'query' => [
                            'client_id' => $this->client_id,
                            'client_secret' => $this->secret,
                            'redirect_uri' => $this->redirect_uri,
                            'code' => $code,
                        ]
                    ]
                )->getBody()->getContents();

        } catch (\Exception $ex) {
            return null;
        }

        return json_decode($vk_response);
    }
}
