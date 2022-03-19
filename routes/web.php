<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShopController;

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
    return redirect()->route('users');
});

Route::get('/dashboard', function () {
    return redirect()->route('users');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth', 'user_type:admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/users', [UserController::class, 'store'])->name('addUsers');
    Route::get('/shops', [ShopController::class, 'index'])->name('shops');
    Route::post('/shops', [ShopController::class, 'store'])->name('addShops');
});


require __DIR__.'/auth.php';