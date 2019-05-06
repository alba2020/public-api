<?php

namespace App;

use App\Exceptions\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SMM {
    public static function validate(Request $request, array $rules) {
        $v = Validator::make($request->all(), $rules);

        if ($v->fails()) {
            throw ValidationException::create(['fields' => $v->errors()]);
        }
    }

    public static function success($data) {
        return response()->json(['success' => $data], Response::HTTP_OK);
    }
}
