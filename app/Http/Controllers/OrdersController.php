<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Exceptions\NotEnoughMediaException;
use App\Exceptions\ValidationException;
use App\Order;
use App\Service;
use App\Services\InstagramScraperService;
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

        $ordersData = json_decode($request->details);
        $createdOrders = [];
        $totalCost = 0;

        foreach($ordersData as $orderData) {
            $orderClass::convert($orderData);
            $orderClass::validate($orderData);
        }
        foreach($ordersData as $orderData) {
            $newOrder = $orderClass::make($service, $user, $orderData->link,
                                            $orderData->quantity);
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
        ], Response::HTTP_CREATED);
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
