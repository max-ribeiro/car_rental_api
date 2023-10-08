<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;
    protected $fillable = [
        'brand_id',
        'name',
        'iamge',
        'doors',
        'seats',
        'air_bag',
        'abs'
    ];

    public function rules() {
        return [
            'brand_id' => 'exists:brands,id',
            'name' => 'required|unique:car_models,name|min:3',
            'image' => 'required|file|mimes:png,jpeg,jpg',
            'doors' => 'required|integer|digits_between:1,5',
            'seats' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'requires|boolean'
        ];
    }
}
