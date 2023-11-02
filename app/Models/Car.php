<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = ['car_model_id', 'plate', 'available', 'km'];

    public function rules() {
        return [
            'car_model_id' => 'exists:carModels,id',
            'plate' => 'required',
            'available' => 'required',
            'km' => 'required'
        ];
    }
    public function carModel() {
        $this->belongsTo(CarModel::class);
    }
}

