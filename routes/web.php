<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// di routes/web.php


Route::get('/', [ProductController::class, 'index']);
Route::post('/cart/add', [ProductController::class, 'addToCart']);
Route::get('/cart', [ProductController::class, 'showCart']);
Route::post('/checkout', [PurchaseController::class, 'checkout']);
Route::get('/tambah_makanan', [ProductController::class, 'create'])->name('products.create');
Route::post('products', [ProductController::class, 'store'])->name('products.store');
Route::get('/', [ProductController::class, 'index'])->name('index');
Route::post('/cart/add', [ProductController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [ProductController::class, 'updateCart'])->name('cart.update');