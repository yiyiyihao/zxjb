<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\StatisticsController;
use \App\Http\Controllers\Api\StoreController;


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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/statistics/homeData', [StatisticsController::class,'homeData']);
Route::post('/statistics/storeData', [StatisticsController::class,'storeData']);
Route::post('/store/storeList', [StoreController::class,'storeList']);
//万佳安学院
Route::post('/store/storeList', [StoreController::class,'storeList']);