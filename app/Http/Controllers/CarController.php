<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Repositories\CarRepository;
use Illuminate\Http\Request;

class CarController extends Controller
{
    private $car;
    public function __construct(Car $car)
    {
        $this->car = $car;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carRepository = new CarRepository($this->car);

        if($request->has('modelParams')) {
            $carRepository->setRelatedRecordsParams('carModel:id'.$request->modelParams);
        } else {
            $carRepository->setRelatedRecordsParams('carModel');
        }
        if($request->has('filters')) {
            $carRepository->setFilters($request->filters);
        }
        if($request->has('params')) {
            $carRepository->setParams($request->params);
        }
        return response()->json($carRepository->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCarRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->car->rules());


        // $car = $this->car->create($request->all());
        $car = $this->car->create([
            'car_model_id' => $request->car_model_id,
            'plate' => $request->plate,
            'available' => $request->available,
            'km' => $request->km
        ]);

        return response()->json($car, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $car = $this->car->with('carModels')->find($id);
        if($car) {
            return response()->json($car, 200);
        }
        return response()->json(['message' => 'marca não encontrada'], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarRequest  $request
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, Request $request)
    {
        $car = $this->car->find($id);

        if($car) {
            $defaultRules = $this->car->rules();
            $params = $request->all();
            $rules = 'PUT' === $request->method()
                ? $defaultRules
                : array_filter($defaultRules , function($key) use ($params) {
                    return array_key_exists($key, $params);
                }, ARRAY_FILTER_USE_KEY);

            $request->validate($rules);

            $car->fill($params);

            $car->save();

            // $car->update($request->all());
            return response()->json($car, 200);
        }
        return response()->json(['message' => 'carro não encontrada'], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $car = $this->car->find($id);
        if($car) {
            $car->delete();
            return response()->json(['message' => 'deleted'], 200);
        }
        return response()->json(['message' => 'marca não encontrada'], 404);
    }
}
