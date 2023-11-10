<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiLoginController as LoginController;
use App\Http\Controllers\ProductsController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->group(function () {
    // Create a new product
    Route::post('/product/add', [ProductsController::class, 'store']);

    // Retrieve all products
    Route::get('/products', [ProductsController::class, 'index']);

    // Retrieve a specific product by ID
    Route::get('/product/{id}', [ProductsController::class, 'show']);

    // Update a product by ID
    Route::put('/product/{id}', [ProductsController::class, 'update']);

    // Delete a product by ID
    Route::delete('/product/{id}', [ProductsController::class, 'destroy']);
});



Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
