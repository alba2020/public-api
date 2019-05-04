<?php

namespace App\Http\Controllers;

use App\Exceptions\EmailAuthorizationException;
use App\Exceptions\EmailExistsException;
use App\Exceptions\FBAuthorizationException;
use App\Exceptions\InvalidCodeException;
use App\Exceptions\MissingParameterException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\VKAuthorizationException;
use App\Mail\ResetEmail;
use App\Mail\VerifyEmail;
use App\Role\UserRole;
use App\Services\SMMAuthService;
use App\SMM;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;


class AuthController extends Controller {

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request, SMMAuthService $smmAuth) {
        // login with email
        if ($request->email && $request->password) {
            SMM::validate($request, [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($token = $smmAuth->loginWithEmail($request->email, $request->password)) {
                return response()->json(['token' => $token], Response::HTTP_OK);
            } else {
                throw EmailAuthorizationException::create();
            }

            // login with vk token
        } else if($request->vk_id && $request->vk_token) {
            SMM::validate($request, [
                'vk_id' => 'required',
                'vk_token' => 'required'
            ]);

            if ($token = $smmAuth->loginWithVK($request->vk_id, $request->vk_token)) {
                return response()->json(['token' => $token], Response::HTTP_OK);
            } else {
                throw VKAuthorizationException::create();
            }

        } else if($request->fb_id && $request->fb_token) {
            SMM::validate($request, [
                'fb_id' => 'required',
                'fb_token' => 'required'
            ]);

            if ($token = $smmAuth->loginWithFB($request->fb_id, $request->fb_token)) {
                return response()->json(['token' => $token], Response::HTTP_OK);
            } else {
                throw FBAuthorizationException::create();
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function loginWithEmail(Request $request, SMMAuthService $smmAuth) {
        SMM::validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($token = $smmAuth->loginWithEmail($request->email, $request->password)) {
            return response()->json(['token' => $token], Response::HTTP_OK);
        } else {
            throw EmailAuthorizationException::create();
        }
    }

    public function loginWithVK(Request $request, SMMAuthService $smmAuth) {
        SMM::validate($request, [
            'vk_id' => 'required',
            'vk_token' => 'required'
        ]);

        if ($token = $smmAuth->loginWithVK($request->vk_id, $request->vk_token)) {
            return response()->json(['token' => $token], Response::HTTP_OK);
        } else {
            throw VKAuthorizationException::create();
        }
    }

    public function loginWithFB(Request $request, SMMAuthService $smmAuth) {
        SMM::validate($request, [
            'fb_id' => 'required',
            'fb_token' => 'required'
        ]);

        if ($token = $smmAuth->loginWithFB($request->fb_id, $request->fb_token)) {
            return response()->json(['token' => $token], Response::HTTP_OK);
        } else {
            throw FBAuthorizationException::create();
        }
    }
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {
        SMM::validate($request, [
            'email' => 'required|email',
            'password' => 'required',
            'password_confirm' => 'required|same:password',
        ]);

        if (User::where('email', $request->email)->first()) {
            throw EmailExistsException::create();
        }

//        $input = $request->all();
//        $input['password'] = bcrypt($input['password']);
//        $user = User::create($input);

        $confirmation_code = str_random(30);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'confirmation_code' => $confirmation_code,
            'roles' => [],
        ]);

        Mail::to($request->email)->send(new VerifyEmail($confirmation_code));

        $success['token'] = $user->createToken('MyApp')->accessToken;
        return response()->json(['success' => $success], Response::HTTP_OK);
    }

    public function confirm($confirmation_code) {
        if (!$confirmation_code) {
            throw MissingParameterException::create(['parameter' => 'confirmation_code']);
        }

        $user = User::where('confirmation_code', $confirmation_code)->first();
        if (!$user) {
            throw InvalidCodeException::create(['text' => 'Invalid confirmation code']);
        }

        $user->addRole(UserRole::ROLE_VERIFIED);
        $user->confirmation_code = null;
        $user->save();

        return response()->json([
            'success' => 'You have successfully verified your account.'
        ], Response::HTTP_OK);
    }

    public function reset(Request $request) {
        SMM::validate($request, [
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            throw UserNotFoundException::create(['text' => 'User not found by email']);
        }

        $reset_code = str_random(30);
        $user->reset_code = $reset_code;
        $user->save();

        Mail::to($request->email)->send(new ResetEmail($reset_code));

        return response()->json([
            'success' => 'Reset code has been sent to your email.'
        ], Response::HTTP_OK);
    }

    public function setPassword(Request $request) {
        SMM::validate($request, [
            'reset_code' => 'required',
            'password' => 'required',
            'password_confirm' => 'required|same:password',
        ]);

        $user = User::where('reset_code', $request->reset_code)->first();
        if (!$user) {
            throw InvalidCodeException::create(['text' => 'Invalid reset code']);
        }

        $user->password = bcrypt($request->password);
        $user->reset_code = null;
        $user->save();

        return response()->json([
            'success' => 'Password changed.'
        ], Response::HTTP_OK);
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
