<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRentalRequest;
use App\Http\Requests\UpdateRentalRequest;
use App\Models\Rental;

use App\Repositories\RentalRepository;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    private $rental;
    public function __construct(Rental $rental)
    {
        $this->rental = $rental;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $rentalRepository = new RentalRepository($this->rental);
        if($request->has('params')) {
            $rentalRepository->setParams($request->params);
        }
        if($request->has('filters')) {
            $rentalRepository->setFilters($request->filters);
        }
        return response()->json($rentalRepository->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRentalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRentalRequest $request)
    {
        $request->validate($this->rental->rules());


        // $rental = $this->rental->create($request->all());
        $rental = $this->rental->create([
            'client_id' => $request->client_id,
            'car_id' => $request->car_id,
            'pickup' => $request->pickup,
            'dropoff' => $request->dropoff,
            'return_date' => $request->return_date,
            'daily_price' => $request->daily_price,
            'initial_km' => $request->initial_km,
            'final_km' => $request->final_km
        ]);

        return response()->json($rental, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rental  $rental
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $rental = $this->rental->find($id);
        if($rental) {
            return response()->json($rental, 200);
        }
        return response()->json(['message' => 'marca não encontrada'], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRentalRequest  $request
     * @param  \App\Models\Rental  $rental
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRentalRequest $request, int $id)
    {
        $rental = $this->rental->find($id);

        if($rental) {
            $defaultRules = $this->rental->rules();
            $params = $request->all();
            $rules = 'PUT' === $request->method()
                ? $defaultRules
                : array_filter($defaultRules , function($key) use ($params) {
                    return array_key_exists($key, $params);
                }, ARRAY_FILTER_USE_KEY);

            $request->validate($rules);

            $rental->fill($params);

            $rental->save();

            // $rental->update($request->all());
            return response()->json($rental, 200);
        }
        return response()->json(['message' => 'locação não encontrada'], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rental  $rental
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $rental = $this->rental->find($id);
        if($rental) {
            $rental->delete();
            return response()->json(['message' => 'deleted'], 200);
        }
        return response()->json(['message' => 'locação não encontrada'], 404);
    }
}
