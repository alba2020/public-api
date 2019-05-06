<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\InsufficientFundsException;
use App\Exceptions\MissingParameterException;
use App\Exceptions\ServerException;
use App\Order;
use App\Service;
use App\Services\NakrutkaService;
use App\SMM;
use App\Transaction;
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
}
