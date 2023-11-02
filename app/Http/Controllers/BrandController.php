<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Repositories\BrandRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    private $brand;

    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $brandRepository = new BrandRepository($this->brand);

        $brands = [];
        if($request->has('model_params')) {
            $brandRepository->setRelatedRecordsParams('carModels:id'.$request->brandParams);
        } else {
            $brandRepository->setRelatedRecordsParams('carModels');
        }
        if($request->has('filters')) {
            $brandRepository->setFilters($request->filters);
        }

        if($request->has('params')) {
            $brandRepository->setParams($request->params);
        }
        return response()->json($brandRepository->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate($this->brand->rules(), $this->brand->feedback());

        $image = $request->file('image');
        $imagePath = $image->store('images/logos', 'public');

        // $brand = $this->brand->create($request->all());
        $brand = $this->brand->create([
            'name' => $request->get('name'),
            'image' => $imagePath
        ]);

        return response()->json($brand, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $brand = $this->brand->with('carmodels')->find($id);
        if($brand) {
            return response()->json($brand, 200);
        }
        return response()->json(['message' => 'marca não encontrada'], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $brand = $this->brand->find($id);

        if($brand) {
            $defaultRules = $this->brand->rules();
            $params = $request->all();
            $rules = 'PUT' === $request->method()
                ? $defaultRules
                : array_filter($defaultRules , function($key) use ($params) {
                    return array_key_exists($key, $params);
                }, ARRAY_FILTER_USE_KEY);

            $request->validate($rules, $this->brand->feedback());

            $image = $request->file('image');
            if($image) {
                Storage::disk('public')->delete($brand->image);
                $imagePath = $image->store('images/logos', 'public');
                $params['image'] = $imagePath;
            }

            $brand->fill($params);

            $brand->save();

            // $brand->update($request->all());
            return response()->json($brand, 200);
        }
        return response()->json(['message' => 'marca não encontrada'], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $brand = $this->brand->find($id);
        if($brand) {
            if ($brand->image) {
                Storage::disk('public')->delete($brand->image);
            }
            $brand->delete();
            return response()->json(['message' => 'deleted'], 200);
        }
        return response()->json(['message' => 'marca não encontrada'], 404);
    }
}
