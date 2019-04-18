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
    // --------------- user ---------------------------
    Route::get('user', 'API\UserController@details');
    Route::post('logout', 'API\UserController@logout');

    // --------------- tasks -------------------
    Route::get('tasks', 'API\TasksController@index');
    Route::get('tasks/{task}', 'API\TasksController@show');
    Route::post('tasks', 'API\TasksController@store');
    Route::put('tasks/{tasks}', 'API\TasksController@update');
    Route::delete('tasks/{task}', 'API\TasksController@delete');

//    Route::post('tasks/run/fake', 'API\TasksController@runFake');
//    Route::post('tasks/run/instagram', 'API\TasksController@runInstagram');
    Route::post('tasks/run/{platform}', 'API\TasksController@runPlatform');

    Route::post('tasks/reset', 'API\TasksController@resetAll');
    Route::post('tasks/undo/{task}', 'API\TasksController@undo');

    // ----------------- fakes ---------------
    Route::get('fakes', 'API\FakesController@index');
});


Route::get('cat', function() {
    return response()->json([
        'cat' => 'the cat',
        'bonus' => 'production branch'
    ], 200);
});


Route::post('vk/token', 'API\VKController@token');
Route::post('fb/token', 'API\FBController@token');


Route::get('/nakrutka', function(Request $request, \App\Services\NakrutkaService $nakrutka) {
//    $order = $nakrutka->add('http://example.com/test', 100);
    $v = $request->input('v');
    // return response()->json($order);
    return response()->json([
        'v' => $v
    ]);
});
