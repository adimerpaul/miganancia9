<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('/login',[\App\Http\Controllers\UserController::class,'login']);
Route::post('upload/{id}/{option}', [\App\Http\Controllers\UploadController::class, 'upload']);
Route::get('agenciaWebSearch/{web}', [\App\Http\Controllers\AgenciaController::class, 'agenciaWebSearch']);
Route::get('agenciaProducts', [\App\Http\Controllers\AgenciaController::class, 'agenciaProducts']);
Route::group(['middleware'=>'auth:sanctum'],function () {
    Route::post('/me', [\App\Http\Controllers\UserController::class, 'me']);
    Route::post('/logout', [\App\Http\Controllers\UserController::class, 'logout']);
    Route::resource('clients', \App\Http\Controllers\ClientController::class);
    Route::resource('sales', \App\Http\Controllers\SaleController::class);
    Route::resource('agencias', \App\Http\Controllers\AgenciaController::class);
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::resource('buys', \App\Http\Controllers\BuyController::class);
    Route::get('productAll', [\App\Http\Controllers\ProductController::class, 'productAll']);
    Route::get('productAllBase64', [\App\Http\Controllers\ProductController::class, 'productAllBase64']);
    Route::post('salesGasto', [\App\Http\Controllers\SaleController::class, 'salesGasto']);
    Route::post('/searchClient', [\App\Http\Controllers\ClientController::class,'searchClient']);
    Route::post('/saleProduct', [\App\Http\Controllers\ReportController::class,'saleProduct']);
});
