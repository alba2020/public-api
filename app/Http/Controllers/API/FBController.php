<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\FBService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class FBController extends Controller {

    public function token(Request $request, FBService $fb)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $token = $fb->getToken($request->post('code'));

        if(!$token) {
            return response()->json([
                'error' => 'Could not get fb access token.'
            ], Response::HTTP_BAD_REQUEST);
        }

        // owner or developer of the app
        // $confirm = $fb->confirmToken($token->access_token, $token->access_token);

        $fb_data = $fb->getUserId($token->access_token);

        if (!$fb_data) {
            return response()->json([
                'error' => 'Could not get fb user id.'
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'token' => $token,
//            'confirm' => $confirm->data,
            'fb_data' => $fb_data,
        ], Response::HTTP_OK);
    }
}
