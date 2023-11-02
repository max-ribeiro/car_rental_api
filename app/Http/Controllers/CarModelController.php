<?php

namespace App\Http\Controllers;

use App\Models\CarModel;
use App\Repositories\CarModelRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarModelController extends Controller
{
    private CarModel $carModel;
    public function __construct(CarModel $carModel)
    {
        $this->carModel = $carModel;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carModelRepository = new CarModelRepository($this->carModel);

        if($request->has('brandParams')) {
            $carModelRepository->setRelatedRecordsParams('brand:id,'.$request->brandParams);
        } else {
            $carModelRepository->setRelatedRecordsParams('brand');
        }
        if($request->has('filters')) {
            $carModelRepository->setFilters($request->filters);
        }
        if($request->has('params')) {
            $carModelRepository->setParams($request->params);
        }
        return response()->json($carModelRepository->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate($this->carModel->rules());
        $params = $request->all();

        $image = $request->file('image');
        $params['image'] = $image->store('images/models', 'public');

        $carModel = $this->carModel->create($params);
        return response()->json($carModel, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CarModel  $carModel
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $carModel = $this->carModel->with('brand')->find($id);
        if($carModel) {
            return response()->json($carModel, 200);
        }
        return response([
            'error' => 'Modelo não existe'
        ], 400);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CarModel  $carModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $carModel = $this->carModel->find($id);
        if($carModel) {
            $params = $request->all();
            $defaultRules = $this->carModel->rules();

            $rules = 'PUT' === $request->method()
                ? $defaultRules
                : array_filter($defaultRules , function($key) use ($params) {
                    return array_key_exists($key, $params);
                }, ARRAY_FILTER_USE_KEY);

            $request->validate($rules);

            $image = $request->file('image');
            if($image) {
                Storage::disk('public')->delete($carModel->image);
                $imagePath = $image->store('images/models', 'public');
                $params['image'] = $imagePath;
            }

            $carModel->fill($params);
            $carModel = $carModel->save();
            return response($carModel, 200);
        }
        return response()->json(['message' => 'modelo não encontrada'], 404);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CarModel  $carModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $carModel = $this->carModel->find($id);
        if($carModel) {
            if ($carModel->image) {
                Storage::disk('public')->delete($carModel->image);
            }
            $carModel->delete();
            return response()->json(['message' => 'deleted'], 200);
        }
        return response()->json(['message' => 'modelo não encontrada'], 404);
    }
}
