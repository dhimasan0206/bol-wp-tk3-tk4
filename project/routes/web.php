<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return to_route('products.index');
});

Auth::routes();

Route::resource('products', ProductController::class)->withTrashed(['show']);
Route::resource('users', UserController::class);
Route::resource('customers', CustomerController::class);
Route::resource('carts', CartController::class);
Route::get('orders/dashboard', [OrderController::class, 'dashboard'])->name('orders.dashboard');
Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
Route::post('/orders/{order}/fulfill', [OrderController::class, 'fulfill'])->name('orders.fulfill');
Route::resource('orders', OrderController::class);
