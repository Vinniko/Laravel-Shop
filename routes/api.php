<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'products'], function (){
    Route::get('/all', [App\Http\Controllers\ProductController::class, 'index']);#->middleware(['auth:api']);
    Route::get('/filter', [App\Http\Controllers\ProductController::class, 'filter']);#->middleware(['auth:api']);
    Route::get('/get/{product:id}', [App\Http\Controllers\ProductController::class, 'show']);#->middleware(['auth:api']);
    Route::post('/store', [App\Http\Controllers\ProductController::class, 'store']);#->middleware(['auth:api']);
    Route::put('/edit/{product:id}', [App\Http\Controllers\ProductController::class, 'edit']);#->middleware(['auth:api']);
    Route::delete('/delete/{product:id}', [App\Http\Controllers\ProductController::class, 'delete']);#->middleware(['auth:api']);
});
