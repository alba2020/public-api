<?php

namespace App\Http\Controllers;

use App\Mail\ResetEmail;
use App\Mail\VerifyEmail;
use App\Role\UserRole;
use App\Services\SMMAuthService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;


class UserController extends Controller {

    public function index() {
        return User::all();
    }

    public function bots($id) {
        $user = User::find($id);
        if(!$user) {
            return response()->json([
                'error' => 'User not found'
            ]);
        }
        return $user->bots()->get()->all();
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

}
