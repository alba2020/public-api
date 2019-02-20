<?php

namespace App\Http\Controllers\API;

use App\Services\SMMAuthService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller {

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request, SMMAuthService $smmAuth) {

//        if ($request->foo) {
//            return response()->json(['message' => 'foo found ' . $request->foo]);
//        } else {
//            return response()->json(['message' => 'foo not found']);
//        }

        // login with email
        if ($request->email && $request->password) {
            $emailValidator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if ($emailValidator->fails()) {
                return response()->json(['error' => $emailValidator->errors()],
                    Response::HTTP_UNAUTHORIZED);
            }

            if ($token = $smmAuth->loginWithEmail($request->email, $request->password)) {
                return response()->json(['token' => $token], Response::HTTP_OK);
            } else {
                return response()->json(['error' => 'Email authorization error'], Response::HTTP_UNAUTHORIZED);
            }

        // login with vk token
        } else if($request->vk_id && $request->vk_token) {

            $vkValidator = Validator::make($request->all(), [
                'vk_id' => 'required',
                'vk_token' => 'required'
            ]);
            if($vkValidator->fails()) {
                return response()->json(['error' => $vkValidator->errors()],
                    Response::HTTP_UNAUTHORIZED);
            }

            if ($token = $smmAuth->loginWithVK($request->vk_id, $request->vk_token)) {
                return response()->json(['token' => $token], Response::HTTP_OK);
            } else {
                return response()->json(['error' => 'VK authorization error'], Response::HTTP_UNAUTHORIZED);
            }

        } else if($request->fb_id && $request->fb_token) {
            $fbValidator = Validator::make($request->all(), [
                'fb_id' => 'required',
                'fb_token' => 'required'
            ]);
            if($fbValidator->fails()) {
                return response()->json(['error' => $fbValidator->errors()],
                    Response::HTTP_UNAUTHORIZED);
            }

            if ($token = $smmAuth->loginWithFB($request->fb_id, $request->fb_token)) {
                return response()->json(['token' => $token], Response::HTTP_OK);
            } else {
                return response()->json(['error' => 'FB authorization error'], Response::HTTP_UNAUTHORIZED);
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'password_confirm' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_UNAUTHORIZED);
        }

//        $input = $request->all();
//        $input['password'] = bcrypt($input['password']);
//        $user = User::create($input);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $success['token'] = $user->createToken('MyApp')->accessToken;
        return response()->json(['success' => $success], Response::HTTP_OK);
    }

    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details() {
        $user = Auth::user();
        return response()->json(['success' => $user], Response::HTTP_OK);
    }

    public function logout() {
//        auth()->logout();
//        Auth::logout();

        if (Auth::check()) {
            Auth::user()->AauthAcessToken()->delete();
        }

        return response()->json(['message' => 'Successfully logged out'], Response::HTTP_OK);
    }
}
