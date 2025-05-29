<?php

use Illuminate\Support\Facades\Route;
use Zplus\Vipos\Http\Controllers\Admin\PosController;
use Zplus\Vipos\Http\Controllers\Admin\PosSessionController;
use Zplus\Vipos\Http\Controllers\Admin\PosTransactionController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/vipos'], function () {
    Route::prefix('vipos')->name('admin.vipos.')->group(function () {
        // POS Dashboard
        Route::get('/', [PosController::class, 'index'])->name('index');
        
        // POS Sessions
        Route::prefix('sessions')->name('sessions.')->group(function () {
            Route::get('/', [PosSessionController::class, 'index'])->name('index');
            Route::post('/open', [PosSessionController::class, 'open'])->name('open');
            Route::post('/{id}/close', [PosSessionController::class, 'close'])->name('close');
            Route::get('/current', [PosSessionController::class, 'getCurrent'])->name('current');
        });
        
        // POS Transactions
        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::get('/', [PosTransactionController::class, 'index'])->name('index');
            Route::post('/checkout', [PosTransactionController::class, 'checkout'])->name('checkout');
            Route::get('/products', [PosTransactionController::class, 'getProducts'])->name('products');
            Route::get('/customers/search', [PosTransactionController::class, 'searchCustomers'])->name('customers.search');
            Route::post('/customers/quick-create', [PosTransactionController::class, 'quickCreateCustomer'])->name('customers.quick-create');
        });
    });
});
