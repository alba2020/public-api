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

    public static function success($data, $code = Response::HTTP_OK) {
        return response()->json(['success' => $data], $code);
    }

    public static function makeGroups(array $collection, array $groups) {
        $o = (object)[];

        foreach($groups as $groupName) {
            $o->$groupName = array_values(array_filter($collection, function($s) use ($groupName) {
                $search = '_' . strtoupper($groupName) . '_'; // _INSTAGRAM_
                return strpos($s->type, $search) !== false;
            }));
        }
        return $o;
    }
}
