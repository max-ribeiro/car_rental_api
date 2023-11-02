<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'car_model_id', 'pickup', 'dropoff', 'return_date', 'daily_price', 'initial_km', 'final_km'];

    public function rules() {
        return [
            'client_id' => '',
            'car_model_id' => '',
            'pickup' => '',
            'dropoff' => '',
            'return_date' => '',
            'daily_price' => '',
            'initial_km' => '',
            'final_km' => ''
        ];
    }
}
