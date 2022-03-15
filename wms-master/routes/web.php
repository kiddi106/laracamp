<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Illuminate\Support\Facades\Auth::routes();
Route::get('/', function () {
    return redirect('/home');
});

Route::middleware('auth')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::name('auth.')->prefix('auth')->group(function () {
        Route::name('user.')->prefix('user')->group(function () {
            Route::post('/datatable', [App\Http\Controllers\Auth\UserController::class, 'datatable'])->name('datatable');
            Route::resource('', App\Http\Controllers\Auth\UserController::class, ['parameters' => ['' => 'id']]);
        });
    });

    Route::name('material.')->prefix('material')->group(function () {
        Route::post('/load_data', [App\Http\Controllers\MaterialController::class, 'loadData'])->name('load_data');
    });

    Route::name('po.')->prefix('po')->group(function () {
        Route::post('/datatable', [App\Http\Controllers\PoController::class, 'datatable'])->name('datatable');
        Route::resource('', App\Http\Controllers\PoController::class, ['parameters' => ['' => 'id']]);

        Route::name('dtl.')->prefix('dtl')->group(function () {
            Route::post('/simcard/datatable', [App\Http\Controllers\PoDtlSimcardController::class, 'datatable'])->name('simcard.datatable');
            Route::post('/simcard/upload', [App\Http\Controllers\PoDtlSimcardController::class, 'upload'])->name('simcard.upload');
            Route::get('/simcard/{po_dtl_id}/create', [App\Http\Controllers\PoDtlSimcardController::class, 'create'])->name('simcard.create');
            Route::post('/simcard', [App\Http\Controllers\PoDtlSimcardController::class, 'store'])->name('simcard.store');
            Route::get('/simcard/{id}/edit', [App\Http\Controllers\PoDtlSimcardController::class, 'edit'])->name('simcard.edit');
            Route::put('/simcard/{id}', [App\Http\Controllers\PoDtlSimcardController::class, 'update'])->name('simcard.update');
            Route::get('/simcard/{po_dtl_id}', [App\Http\Controllers\PoDtlSimcardController::class, 'index'])->name('simcard.index');

            Route::post('/router/datatable', [App\Http\Controllers\PoDtlRouterController::class, 'datatable'])->name('router.datatable');
            Route::post('/router/upload', [App\Http\Controllers\PoDtlRouterController::class, 'upload'])->name('router.upload');
            Route::get('/router/{po_dtl_id}/create', [App\Http\Controllers\PoDtlRouterController::class, 'create'])->name('router.create');
            Route::post('/router', [App\Http\Controllers\PoDtlRouterController::class, 'store'])->name('router.store');
            Route::get('/router/{id}/edit', [App\Http\Controllers\PoDtlRouterController::class, 'edit'])->name('router.edit');
            Route::put('/router/{id}', [App\Http\Controllers\PoDtlRouterController::class, 'update'])->name('router.update');
            Route::get('/router/{po_dtl_id}', [App\Http\Controllers\PoDtlRouterController::class, 'index'])->name('router.index');

            Route::resource('', App\Http\Controllers\PoDtlController::class, ['parameters' => ['' => 'id']]);
        });
    });

    Route::name('rework.')->prefix('rework')->group(function () {
        Route::post('/datatable', [App\Http\Controllers\ReworkController::class, 'datatable'])->name('datatable');
        Route::post('/upload', [App\Http\Controllers\ReworkController::class, 'upload'])->name('upload');
        // Route::post('/load_data', [App\Http\Controllers\ReworkController::class, 'loadData'])->name('load_data');
        Route::post('/get_orbit', [App\Http\Controllers\ReworkController::class, 'getOrbit'])->name('stock');
        Route::resource('', App\Http\Controllers\ReworkController::class, ['parameters' => ['' => 'id']]);
    });

    Route::name('router.')->prefix('router')->group(function () {
        Route::post('/load_data', [App\Http\Controllers\RouterController::class, 'loadData'])->name('load_data');
        Route::resource('', App\Http\Controllers\RouterController::class, ['parameters' => ['' => 'id']]);
    });

    Route::name('simcard.')->prefix('simcard')->group(function () {
        Route::post('/load_data', [App\Http\Controllers\SimcardController::class, 'loadData'])->name('load_data');
        Route::resource('', App\Http\Controllers\SimcardController::class, ['parameters' => ['' => 'id']]);
    });

    Route::name('pickpack.')->prefix('pickpack')->group(function () {
        Route::name('dtl.')->prefix('dtl')->group(function () {
            Route::get('/items/{order_item_id}', [App\Http\Controllers\PickPackDtlController::class, 'showItems'])->name('show.items');
            Route::post('/datatable', [App\Http\Controllers\PickPackDtlController::class, 'datatable'])->name('datatable');
            Route::resource('item', App\Http\Controllers\PickPackDtlItemController::class, ['parameters' => ['item' => 'id']]);
        });
        Route::get('/print_awb/{id}', [App\Http\Controllers\PickpackController::class, 'printAwb'])->name('print_awb');
        Route::post('/datatable', [App\Http\Controllers\PickpackController::class, 'datatable'])->name('datatable');
        Route::post('/pick_ups', [App\Http\Controllers\PickpackController::class, 'pickUps'])->name('pick_ups');
        Route::resource('', App\Http\Controllers\PickpackController::class, ['parameters' => ['' => 'id']]);
    });

    Route::name('orbitstock.')->prefix('orbitstock')->group(function () {
        Route::post('/load_data', [App\Http\Controllers\OrbitStockController::class, 'loadData'])->name('load_data');
        // Route::resource('', App\Http\Controllers\OrbitStockController::class, ['parameters' => ['' => 'id']])
    });
    Route::name('order.')->prefix('order')->group(function () {
        // Route::post('/load_data', [App\Http\Controllers\SimcardController::class, 'loadData'])->name('load_data');
        Route::get('/report/{date}', [App\Http\Controllers\OrderController::class, 'report'])->name('report');
        Route::post('/datatable', [App\Http\Controllers\OrderController::class, 'datatable'])->name('datatable');
        Route::resource('', App\Http\Controllers\OrderController::class, ['parameters' => ['' => 'id']]);
    });
});

Route::get('/logout', function () {
    Illuminate\Support\Facades\Auth::logout();
    return redirect('/login');
});
