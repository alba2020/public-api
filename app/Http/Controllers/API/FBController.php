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
        $confirm = $fb->confirmToken($token->access_token, $token->access_token);

        return response()->json([
            'token' => $token,
            'confirm' => $confirm->data,
        ], Response::HTTP_OK);
    }
}
