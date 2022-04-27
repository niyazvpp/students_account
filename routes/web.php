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

Route::middleware('web')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/users', [UserController::class, 'store'])->name('addUsers');
    Route::get('/teachers', [UserController::class, 'users'])->name('teachers');
    Route::get('/parents', [UserController::class, 'parents'])->name('parents');
});


require __DIR__.'/auth.php';