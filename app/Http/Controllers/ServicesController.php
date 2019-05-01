<?php

namespace App\Http\Controllers;

use App\Exceptions\CostException;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ServicesController extends Controller {

    public function index() {
        return Service::all();
    }

    public function cost($service_id, $n) {
        $service = Service::find($service_id);

        try {
            return response()->json([
                'success' => Service::getCost($service, $n),
            ]);
        } catch (CostException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }
    }


    /**
     * @param $paramsJson [{service_id: 1, n: 200}, {service_id: 2, n: 300}]
     */
    public function costs(Request $request) {
        $paramsJson = $request->params;

        $validator = Validator::make($request->all(), [
            'params' => 'required|json',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()],
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $params = json_decode($paramsJson);
        $servicesIds = array_map(function($x){ return $x->service_id; }, $params);
        $services = Service::whereIn('id', $servicesIds)->get()->all();

        try {
            $res = array_map(function($param) use (&$services) {
                $service = current(array_filter($services, function($service) use($param) {
                    return $service->id === $param->service_id;
                }));

                if(!$service) {
                    throw new CostException("service not found: $param->service_id");
                }

                return Service::getCost($service, $param->n);
            }, $params);
            return response()->json([
                'success' => $res,
            ], Response::HTTP_OK);
        } catch (CostException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
