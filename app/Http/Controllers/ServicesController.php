<?php

namespace App\Http\Controllers;

use App\Exceptions\CostException;
use App\Exceptions\EntityNotFoundException;
use App\Service;
use App\SMM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ServicesController extends Controller {

    public function index() {
        return Service::all();
    }

    /**
     * @param $service_id
     * @param $n
     * @return \Illuminate\Http\JsonResponse
     * @throws CostException
     */
    public function cost($service_id, $n) {
        $service = Service::find($service_id);
        if(!$service) {
            throw EntityNotFoundException::create(['text' => 'Service not found']);
        }

        return response()->json([
            'success' => Service::getCost($service, $n),
        ]);
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

            return Service::getCost($service, $param->n);
        }, $params);

        return response()->json([
            'success' => $res,
        ], Response::HTTP_OK);
    }
}
