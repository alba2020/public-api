<?php

namespace App\Http\Controllers\API;

use App\Fake;
use App\Http\Controllers\Controller;
use Validator;

class FakesController extends Controller
{
    public function index()
    {
        return Fake::all();
    }
}
