<?php

namespace App;

use App\Exceptions\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SMM {
    public static function validate(Request $request, array $rules) {
        $v = Validator::make($request->all(), $rules);

        if ($v->fails()) {
            throw ValidationException::create(['fields' => $v->errors()]);
        }
    }
}
