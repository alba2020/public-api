<?php

namespace App\Http\Controllers;

use App\Exceptions\CostException;
use App\Exceptions\EntityNotFoundException;
use App\Service;
use App\SMM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ServicesController extends Controller {

    public function index() {
        return Service::all();
    }

    public function indexGrouped() {
        $services = Service::get()->all();

        $o = SMM::makeGroups($services, ['instagram', 'vk', 'auto']);
        return response()->json($o, Response::HTTP_OK);
    }

    public function getByType($type) {
        $service = Service::where('type', $type)->first();

        if(!$service) {
            throw EntityNotFoundException::create(['text' => 'Service not found']);
        }

        return SMM::success($service);
    }

    /**
     * @param $service_id
     * @param $n
     * @return \Illuminate\Http\JsonResponse
     * @throws CostException
     */
    public function cost($service_id, $n) {
        $service = Service::findOrFail($service_id);
//        if(!$service) {
//            throw EntityNotFoundException::create(['text' => 'Service not found']);
//        }

        return SMM::success($service->computeCost($n));
    }

    public function costPremium($service_id, $n) {
        $service = Service::findOrFail($service_id);
        $user = Auth::user();

        $costData = $service->computeCostPremium($n, $user);
        return response()->json($costData, Response::HTTP_OK);
    }

    /**
     * @param $paramsJson [{service_id: 1, n: 200}, {service_id: 2, n: 300}]
     */
    public function costs(Request $request) {
        SMM::validate($request, [
            'params' => 'required|json',
        ]);

        $params = json_decode($request->params);
        $servicesIds = array_map(function($x){ return $x->service_id; }, $params);
        $services = Service::whereIn('id', $servicesIds)->get()->all();

        $res = array_map(function($param) use (&$services) {
            $service = current(array_filter($services, function($service) use($param) {
                return $service->id === $param->service_id;
            }));

            if(!$service) {
                throw EntityNotFoundException::create([
                    'text' => "Service not found: $param->service_id"]);
            }

            return $service->computeCost($param->n);
        }, $params);

        return SMM::success($res);
    }

    public function costsPremium(Request $request) {
        SMM::validate($request, [
            'params' => 'required|json',
        ]);

        $params = json_decode($request->params);
        $servicesIds = array_map(function($x){ return $x->service_id; }, $params);
        $services = Service::whereIn('id', $servicesIds)->get()->all();

        $res = array_map(function($param) use (&$services) {
            $service = current(array_filter($services, function($service) use($param) {
                return $service->id === $param->service_id;
            }));

            if(!$service) {
                throw EntityNotFoundException::create([
                    'text' => "Service not found: $param->service_id"]);
            }

            return $service->computeCostPremium($param->n, Auth::user());
        }, $params);

        return SMM::success($res);
    }
}
