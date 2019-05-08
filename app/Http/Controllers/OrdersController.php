<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\InsufficientFundsException;
use App\Exceptions\MissingParameterException;
use App\Exceptions\ServerException;
use App\Exceptions\ValidationException;
use App\Order;
use App\Role\UserRole;
use App\Service;
use App\Services\NakrutkaService;
use App\SMM;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OrdersController extends Controller {
    public function index() {
        return Order::all();
    }

    public function show(Order $order) {
        return $order;
    }

    public function byUUID($uuid) {
        return Order::byUUID($uuid);
    }

    public function store(Request $request, NakrutkaService $nakrutka) {
        SMM::validate($request, [
            'details' => 'required|json'
        ]);

        $details = json_decode($request->details);
        if(!$details->service_id) {
            throw MissingParameterException::create(['text' => 'service_id']);
        }

        if(!$details->url) {
            throw MissingParameterException::create(['text' => 'url']);
        }

        if(!$details->n) {
            throw MissingParameterException::create(['text' => 'n']);
        }

        $service = Service::find($details->service_id);
        if (!$service) {
            throw EntityNotFoundException::create(['text' => 'service']);
        }

        $user = Auth::user();

        $cost = $service->getCost($details->n);
        $wallet = $user->wallet;

        if ($wallet->balance < $cost) {
            throw InsufficientFundsException::create();
        }

        try {
            $order = Order::create([
                'user_id' => $user->id,
                'service_id' => $details->service_id,
                'details' => (array) $details,
                'cost' => $cost,
            ]);

            $order->refresh(); // load defaults from db
        } catch (\Exception $e) {
            throw ServerException::create(['text' => $e->getMessage()]);
        }

//        $user->balance -= $cost;
//        $user->save();

        $wallet->applyTransaction(Constants::OUTFLOW_ORDER, (-1) * $cost, "Order $order->id");

        $nakrutka->setApiService($service->nakrutka_id);
        $nakrutka->add('ftp://bad_url' . $details->url, $details->n);

        return response()->json($order, Response::HTTP_CREATED);
    }

    // создать заказ
    public function create(Request $request) {
        $user = Auth::user();
        return $this->_store($request, $user);
    }

    public function guestCreate(Request $request) {
        $r = str_random(8);
        $user = User::create([
            'name' => 'user_' . $r,
            'password' => bcrypt($r),
            'email' => $r . '@smm.example.com',
            'roles' => [UserRole::ROLE_AUTO],
        ]);
        return $this->_store($request, $user);
    }

    protected function _store(Request $request, $user) {
        SMM::validate($request, [
            'details' => 'required|json'
        ]);

        $details = json_decode($request->details);
        if(!$details->service_id) {
            throw MissingParameterException::create(['text' => 'service_id']);
        }
        $service = Service::findOrFail($details->service_id);
        $orderClass = Constants::subclasses[$service->type];
        if (!$orderClass) {
            throw ValidationException::create(['text' => 'Bad service type']);
        }

        $orderClass::validate($details);

        $newOrder = $orderClass::make($service, $user, $details);
//        dd($newOrder);

        if ($user->wallet->balance < $newOrder->cost) {
            // compute payment parameters
            $paymentString = "http://kassa.com/babki?davai";
            $enoughFunds = false;
        } else {
            $paymentString = "";
            $enoughFunds = true;
        }

        return SMM::success([
            'order' => Order::with('user')->find($newOrder->id),
            'payment_string' => $paymentString,
            'enough_funds' => $enoughFunds,
        ]);
    }

    public function executeByUUID(string $uuid) {
        $order = Order::byUUID($uuid);
        $order->pay();
        return $order->run();
    }

//    public function spreadLikes(Request $request, NakrutkaService $nakrutka) {
//        SMM::validate($request, [
//            'details' => 'required|json'
//        ]);
//
//        // service_id нинада
//        // $service = ::GetServiceByType('INSTAGRAM_LIKES');
//        // validate presence {instagram_login, likes_per_post, posts}
//
//        // сделать
//
//        $user = Auth::user();
//
//        $cost = $service->getCost($details->posts * $details->likes_per_post);
//        $wallet = $user->wallet;
//
//
//        $cost = $service->
//    }
}
