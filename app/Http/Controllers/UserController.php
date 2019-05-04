<?php

namespace App\Http\Controllers;

use App\Exceptions\UserNotFoundException;
use App\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class UserController extends Controller {

    public function index() {
        return User::all();
    }

    public function bots($id) {
        $user = User::find($id);
        if(!$user) {
            throw UserNotFoundException::create(['id' => $id]);
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
