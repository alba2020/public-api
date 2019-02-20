<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\VKService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class VKController extends Controller {

    public function token(Request $request, VKService $vk)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $vk_response = $vk->getToken($request->post('code'));

        return response()->json($vk_response, Response::HTTP_OK);
    }
}
