<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\InsufficientFundsException;
use App\Exceptions\MissingParameterException;
use App\Exceptions\NotEnoughMediaException;
use App\Exceptions\ServerException;
use App\Exceptions\ValidationException;
use App\Order;
use App\Role\UserRole;
use App\Rules\JSONContains;
use App\Service;
use App\Services\InstagramScraperService;
use App\Services\NakrutkaService;
use App\SMM;
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

    public function batchCreate(Request $request, Service $service) {
        $user = Auth::user();
        return $this->_batchStore($request, $service, $user);
    }

    public function guestBatchCreate(Request $request, Service $service) {
        $user = User::createAuto(8);
        return $this->_batchStore($request, $service, $user);
    }

    protected function _batchStore(Request $request, Service $service, User $user) {
        SMM::validate($request, [
            'details' => ['required', 'json'],
        ]);

        $orderClass = Constants::subclasses[$service->type];
        if (!$orderClass) {
            throw ValidationException::create(['text' => 'Bad service type']);
        }

        $details = json_decode($request->details);
        $createdOrders = [];
        $totalCost = 0;

        foreach($details as $orderDetails) {
            $orderClass::convert($orderDetails);
            $orderClass::validate($orderDetails);
        }
        foreach($details as $orderDetails) {
            $newOrder = $orderClass::make($service, $user, $orderDetails);
            $createdOrders[] = $newOrder;
            $totalCost += $newOrder->cost;
        }

        if ($user->wallet->balance < $totalCost) {
            // compute payment parameters
            $paymentString = "http://kassa.com/?amount=" . ($totalCost - $user->wallet->balance);
        } else {
            $paymentString = "";
        }

        return SMM::success([
            'orders' => $createdOrders,
            'total' => $totalCost,
            'payment_string' => $paymentString,
        ]);
    }

    // создать заказ
    public function create(Request $request) {
        $user = Auth::user();
        return $this->_store($request, $user);
    }

    public function guestCreate(Request $request) {
        $user = User::createAuto(8);
        return $this->_store($request, $user);
    }

    protected function _store(Request $request, $user) {
        SMM::validate($request, [
            'details' => ['required', 'json', new JSONContains('service_id')],
        ]);

        $details = json_decode($request->details);
        $service = Service::findOrFail($details->service_id);
        $orderClass = Constants::subclasses[$service->type];
        if (!$orderClass) {
            throw ValidationException::create(['text' => 'Bad service type']);
        }

        $orderClass::convert($details);
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
        $order->run();
        return response()->json($order, Response::HTTP_OK);
    }

    public function batchExecuteByUUID(Request $request) {
        SMM::validate($request, [
            'details' => ['required', 'json'],
        ]);

        $details = json_decode($request->details);
        $runningOrders = [];
        foreach($details as $uuid) {
            $order = Order::byUUID($uuid);
            if ($order->status !== Order::STATUS_RUNNING &&
                $order->status !== Order::STATUS_COMPLETED) {
                $order->pay();
                $order->run();
                $runningOrders[] = $order;
            }
        }
        return SMM::success($runningOrders);
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

    public function spread(Request $request) {
        SMM::validate($request, [
            'instagram_login' => 'required',
            'posts' => 'required',
        ]);

        $scraper = app()->make(InstagramScraperService::class);
        $scraper->checkLoginNotPrivate($request->instagram_login);

        $urls = $scraper->getMediaURLs(
            $request->instagram_login,
            $request->posts
        );

        if(count($urls) < $request->posts) {
            throw NotEnoughMediaException::create([
                'amount' => $request->posts,
                'actual' => count($urls),
            ]);
        }

        return SMM::success(['posts' => $urls]);
    }
}
