<?php

namespace App\Http\Controllers;

use App\Models\Brand;
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
    public function index()
    {
        $allBrands = $this->brand->all();
        if($allBrands) {
            return response()->json($allBrands, 200);
        }
        return response()->json(['Nenhuma marca encontrada'], 404);
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
        $imagePath = $image->store('images', 'public');

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
        $brand = $this->brand->find($id);
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
            $requestParams = $request->all();
            $rules = 'PUT' === $request->method()
                ? $defaultRules
                : array_filter($defaultRules , function($key) use ($requestParams) {
                    return array_key_exists($key, $requestParams);
                }, ARRAY_FILTER_USE_KEY);

            $request->validate($rules, $this->brand->feedback());
            $image = $request->file('image');
            $imagePath = $image->store('/images', 'public');

            $params = [];
            if ($imagePath) {
                if ($brand->image) {
                    Storage::disk('public')->delete($brand->image);
                }
                $params['image'] = $imagePath;
            }

            $name = $request->name;
            if ($name) {
                $params['name'] = $name;
            }

            $brand->update($params);

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
