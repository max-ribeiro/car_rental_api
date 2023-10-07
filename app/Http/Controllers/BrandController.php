<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;
use Ramsey\Uuid\Type\Integer;

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
        return $allBrands;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $brand = $this->brand->create($request->all());
        return $brand;
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
        return $brand;
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
        $brand->update($request->all());
        return $brand;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $brand = $this->brand->find($id);
        $brand->delete();
        return ['message' => 'deleted'];
    }
}
