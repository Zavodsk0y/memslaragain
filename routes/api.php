<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ProductController;
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

Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);

Route::resource('/products', ProductController::class)->only(['index', 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::resource('/products', ProductController::class)->only(['store', 'update', 'destroy']);
    Route::resource('/cart', CartController::class)->only('index');
    Route::post('/cart/{product}', [CartController::class, 'store']);
    Route::delete('/cart/{cartProduct}', [CartController::class, 'destroy']);
    Route::resource('/files', FileController::class)->only(['store']);
});
