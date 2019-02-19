<?php

namespace App\Http\Controllers\API;

use App\Services\VKService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;


class VKController extends Controller {

    public function token(Request $request, VKService $vk)
    {
//        $client = new \GuzzleHttp\Client();
//
//        $response = $client->request('GET', 'http://mail.ru');
//        $response = $response->getBody()->getContents();
//        print_r($response);

//        Response::HTTP_OK;
//        return 'hello';
        $validator = Validator::make($request->all(), [
            'code' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $vk_response = $vk->getToken($request->post('code'));

//        var_dump($vk_response);
//        echo $vk_response->access_token;
//        echo $vk_response->user_id;

        return response()->json($vk_response, 200);
    }
}
