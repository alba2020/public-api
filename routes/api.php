<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('user', 'API\UserController@details');
    Route::post('logout', 'API\UserController@logout');

    Route::get('tasks', 'TasksController@index');
    Route::get('tasks/{task}', 'TasksController@show');
    Route::post('tasks', 'TasksController@store');
    Route::put('tasks/{tasks}', 'TasksController@update');
    Route::delete('tasks/{task}', 'TasksController@delete');
});


Route::get('cat', function() {
    return response()->json([
        'cat' => 'the cat'
    ], 200);
});


Route::post('vk/token', 'API\VKController@token');
