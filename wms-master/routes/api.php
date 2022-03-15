<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\StockController;
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

// Route::group([
//     'middleware' => 'api',
//     'prefix' => 'api/auth'

// ], function () {
//     Route::post('/login', [AuthController::class, 'login']);
//     Route::post('/register', [AuthController::class, 'register']);
//     Route::post('/logout', [AuthController::class, 'logout']);
//     Route::post('/refresh', [AuthController::class, 'refresh']);
//     Route::get('/user-profile', [AuthController::class, 'userProfile']);
// });


Route::middleware(['api'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/user-profile', [AuthController::class, 'userProfile']);
    });

    Route::group(['middleware' => ['jwt.verify']], function() {
        Route::post('/order/inbound', [OrderController::class, 'inbound']);
        Route::post('/order/outbound', [OrderController::class, 'outbound']);
        Route::post('/order/pick_up', [OrderController::class, 'pick_up']);
        Route::post('/order/list', [OrderController::class, 'list']);
        Route::post('/order', [OrderController::class, 'store']);

        Route::post('/stock', [StockController::class, 'index']);
    });
});
