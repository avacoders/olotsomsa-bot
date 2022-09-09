<?php

use App\Helpers\Geolocation;
use App\Helpers\Telegram;
use App\Http\Controllers\Api\GeolocationController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\ExceptionController;
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

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::post('/update-profile', [LoginController::class, 'update']);
    Route::get('/my-orders', [ProductController::class, 'myOrders']);
    Route::post("/order", [OrderController::class, 'store']);
    Route::delete("/self-delete", [LoginController::class, 'selfDelete']);
});


Route::group(['prefix' => 'products'], function () {
    Route::get('/', [ProductController::class, 'index']);
});

Route::post('/login', [LoginController::class, 'login']);
Route::post('/code/{phone_number}', [LoginController::class, 'code']);
Route::post('/name/{phone_number}', [LoginController::class, 'name']);


Route::get('/locations', [GeolocationController::class, 'index']);
Route::get('/branches', [GeolocationController::class, 'show']);

Route::post('/bot', [Telegram::class, 'sendConfirmation']);

Route::post("/exception", [ExceptionController::class, 'index']);
