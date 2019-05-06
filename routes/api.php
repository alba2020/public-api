<?php

use App\Role\UserRole;
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


Route::post('login', 'AuthController@login'); // deprecated
Route::post('login/email', 'AuthController@loginWithEmail');
Route::post('login/vk', 'AuthController@loginWithVK');
Route::post('login/fb', 'AuthController@loginWithFB');

Route::post('register', 'AuthController@register');
Route::get('confirm/{confirmationCode}', 'AuthController@confirm')
            ->name('confirmation_path');
Route::post('reset', 'AuthController@reset');
Route::post('set_password', 'AuthController@setPassword');

Route::get('services', 'ServicesController@index');
Route::get('services/{service_id}/cost/{n}', 'ServicesController@cost');
Route::post('services/costs', 'ServicesController@costs');


Route::group(['middleware' => 'auth:api'], function() {
    // --------------- user ---------------------------
    Route::get('user', 'UserController@details');
    Route::post('logout', 'AuthController@logout');

    Route::get('orders', 'OrdersController@index');
    Route::post('orders', 'OrdersController@store');


    Route::group(['middleware' => 'check_user_role:' . UserRole::ROLE_MODERATOR],
        function() {
            Route::get('users', 'UserController@index');
        });

    Route::get('users/{id}/bots', 'UserController@bots');
    Route::get('users/{id}/orders', 'UserController@orders');
    Route::get('users/{id}/transactions', 'UserController@transactions');

    // --------------- tasks -------------------
    Route::get('tasks', 'TasksController@index');
    Route::get('tasks/{task}', 'TasksController@show');
    Route::post('tasks', 'TasksController@store');
    Route::put('tasks/{tasks}', 'TasksController@update');
    Route::delete('tasks/{task}', 'TasksController@delete');

//    Route::post('tasks/run/fake', 'API\TasksController@runFake');
//    Route::post('tasks/run/instagram', 'API\TasksController@runInstagram');
    Route::post('tasks/run/{platform}', 'TasksController@runPlatform');

    Route::post('tasks/reset', 'TasksController@resetAll');
    Route::post('tasks/undo/{task}', 'TasksController@undo');

    // ----------------- fakes ---------------
    Route::get('fakes', 'FakesController@index');

    // -- roles --

//    Route::get('support', function() {
//        return response()->json([
//            'msg' => 'support text'
//        ]);
//    })->middleware('check_user_role:' . UserRole::ROLE_SUPPORT);
//
//    Route::get('blog', function() {
//        return response()->json([
//            'msg' => 'blog text'
//        ]);
//    })->middleware('check_user_role:' . UserRole::ROLE_BLOGGER);

});

Route::get('cat', function() {
    return response()->json([
        'cat' => 'the cat',
        'bonus' => 'production branch'
    ], 200);
});

Route::get('excat', function() {
//    throw new \App\Exceptions\CatException(1);
    throw \App\Exceptions\CatException::create(['n' => 12]);
//   return response()->json([
//       'hello' => 'world',
//   ]);
});

Route::post('vk/token', 'VKController@token');
Route::post('fb/token', 'FBController@token');


Route::get('/nakrutka', function(Request $request, \App\Services\NakrutkaService $nakrutka) {
//    $order = $nakrutka->add('http://example.com/test', 100);
    $v = $request->input('v');
    // return response()->json($order);
    return response()->json([
        'v' => $v
    ]);
});
