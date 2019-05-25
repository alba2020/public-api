<?php

namespace App;

use App\Exceptions\BadParameterException;
use App\Exceptions\MissingParameterException;
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

    public static function withLink($details) {
        if (!isset($details->link)) {
            throw MissingParameterException::create(['text' => 'link missing']);
        }
        return $details;
    }

    public static function withQuantity($details) {
        if (!isset($details->quantity)) {
            throw MissingParameterException::create(['text' => 'quantity missing']);
        }
        return $details;
    }

    public static function withMinQuantity100($details) {
        if ($details->quantity < 100) {
            throw BadParameterException::create([
                'text' => 'quantity must be >= 100'
            ]);
        }
        return $details;
    }

    public static function withUsername($details) {
        if (!isset($details->username)) {
            throw MissingParameterException::create(['text' => 'username missing']);
        }
        return $details;
    }

    public static function withMinAndMax($details) {
        if (!isset($details->min)) {
            throw MissingParameterException::create(['text' => 'min missing']);
        }
        if (!isset($details->max)) {
            throw MissingParameterException::create(['text' => 'max missing']);
        }
        return $details;
    }

    public static function withPosts($details) {
        if (!isset($details->posts)) {
            throw MissingParameterException::create(['text' => 'posts missing']);
        }
        return $details;
    }
}
