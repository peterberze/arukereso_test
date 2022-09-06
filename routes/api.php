<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

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

Route::post('create-order', function(Request $request) {
    return OrderController::createOrder($request);
});

Route::post('change-status', function(Request $request) {
    return OrderController::changeStatus($request);
});

Route::post('list-orders', function(Request $request) {
    return OrderController::listOrders($request);
});
