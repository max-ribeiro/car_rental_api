<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CarModelController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\RentalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->middleware('jwt.auth')->group(function() {
    Route::apiResource('brand', BrandController::class);
    Route::apiResource('car', CarController::class);
    Route::apiResource('model', CarModelController::class);
    Route::apiResource('client', ClientController::class);
    Route::apiResource('rental', RentalController::class);
    //token controllers
    Route::group([], function() {
        Route::post('me', AuthController::class.'@me');
        Route::post('refresh', AuthController::class.'@refresh');
        Route::post('logout', AuthController::class.'@logout');
    });
});

Route::post('login', AuthController::class.'@login');
