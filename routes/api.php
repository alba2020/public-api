<?php

use App\Role\UserRole;
use App\Rules\JSONContains;
use App\Service;
use App\SMM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


//Route::post('login', 'AuthController@login'); // deprecated
Route::post('login/email', 'AuthController@loginWithEmail');
Route::post('login/vk', 'AuthController@loginWithVK');
Route::post('login/fb', 'AuthController@loginWithFB');

Route::post('register', 'AuthController@register');
Route::get('confirm/{confirmationCode}', 'AuthController@confirm')
            ->name('confirmation_path');
Route::post('reset', 'AuthController@reset');
Route::post('set_password', 'AuthController@setPassword');

Route::get('services', 'ServicesController@index');
Route::get('services/g', 'ServicesController@indexGrouped');

Route::get('services/{service_id}/cost/{n}', 'ServicesController@cost');
Route::post('services/costs', 'ServicesController@costs');


// ------------- orders --------------------
Route::post('orders/guest', 'OrdersController@guestBatchCreate');
Route::get('orders/uuid/{uuid}', 'OrdersController@byUUID');
// details - JSON
// ["9823316e6b16f7454e65f4ccea3a36f9", "46471933fc65fd218f4581e3974ff1f5"]
Route::post('orders/execute/uuid', 'OrdersController@batchExecuteByUUID');

Route::post('orders/spread', 'OrdersController@spread');


Route::group(['middleware' => 'auth:api'], function() {
    // --------------- user ---------------------------
    Route::get('user', 'UserController@details');
    Route::post('logout', 'AuthController@logout');

    Route::get('orders', 'OrdersController@index');
//    Route::post('orders', 'OrdersController@create');

// details: JSON
//[
// {"url": "https://www.instagram.com/p/BxFMFbeAjoL", "n": 102},
// {"url": "https://www.instagram.com/p/BxFMFbeAjoL", "n": 100}
//]
    Route::post('orders/service/{service}', 'OrdersController@batchCreate');

    Route::group(['middleware' => 'check_user_role:' . UserRole::ROLE_MODERATOR],
        function() {
            Route::get('users', 'UserController@index');
        });

    Route::get('users/{id}/bots', 'UserController@bots');
    Route::get('users/{id}/orders', 'UserController@orders');
    Route::get('users/{id}/orders/g', 'UserController@ordersGrouped');
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

    $svc = app()->make('\App\Services\InstagramScraperService');

    return response()->json([
        'cat' => $svc->getCat('hello'),
    ], 200);
});

Route::post('cat', function(Request $request) {
    SMM::validate($request, [
        'details' => ['required', 'json', new JSONContains('param')],
    ]);

    return response()->json([
        'cat' => 'the cat',
    ], 200);
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
